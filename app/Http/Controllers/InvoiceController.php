<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use PDF;


class InvoiceController extends Controller
{



     
    function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $invoices = Invoice::latest()->get();
        return view('invoice.index',compact('invoices'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
        session(['tr_row' => 0]);
        $row = session()->get('tr_row');
        return view('invoice.create',compact('row'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        $request->validate([
            'invoice_number'=>['required'],
            'invoice_date'=>['required'],
            'billing_address'=>['required'],
            'shipping_address'=>['required'],
        ]);

        DB::beginTransaction();
         session()->forget('tr_row');
        try{

            $sub_total_amount = 0;
        
            $invoice = new Invoice();
            $invoice->user_id = Auth::id();
            $invoice->invoice_number = $request->invoice_number;
            $invoice->order_date = Carbon::parse($request->order_date);
            $invoice->billing_address = $request->billing_address;
            $invoice->shipping_address = $request->shipping_address;
            $invoice->discount_amount = $request->discount_amount;
            $invoice->save();
    
            $i = 0;
            foreach($request->name as $name){
              $item = new InvoiceItem();
              $item->invoice_id = $invoice->id;
              $item->product_name = $name;
              $item->price = $request->price[$i];
              $item->quantity = $request->quantity[$i];
              $item->sub_total = $item->quantity * $item->price;
              $item->save();
              $i++;
              $sub_total_amount += $item->sub_total;
            }
            $invoice->sub_total_amount =  $sub_total_amount;
            $invoice->total_amount = ($invoice->sub_total_amount - $invoice->discount_amount);
            $invoice->save();

            DB::commit();
            alert()->success('Success','Invoice is created');
        }catch (Exception $ex){
            DB::rollBack();
            alert()->warning('Problem','Here are some issues, please try again');
        }
        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $invoice = Invoice::where('id',$id)->with('items')->first();
        // return $invoice;
        return view('invoice.show',compact('invoice'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $invoice = Invoice::where('id',$id)->with('items')->first();
        session(['tr_row' => $invoice->items->count()-1]);
        return view('invoice.edit',compact('invoice'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        // return $request;
        $request->validate([
            'invoice_number'=>['required'],
            'invoice_date'=>['required'],
            'billing_address'=>['required'],
            'shipping_address'=>['required'],
        ]);

        DB::beginTransaction();
         session()->forget('tr_row');
        // try{

            $sub_total_amount = 0;
        
            $invoice = Invoice::where('id',$request->id)->with('items')->first();
            $invoice->user_id = Auth::id();
            $invoice->order_date = Carbon::parse($request->order_date);
            $invoice->billing_address = $request->billing_address;
            $invoice->shipping_address = $request->shipping_address;
            $invoice->discount_amount = $request->discount_amount;
            $invoice->save();
         
            // delete the items here
            foreach($invoice->items as $item){
                $item->delete();
            }
            
            $i = 0;
            foreach($request->name as $name){
              $item = new InvoiceItem();
              $item->invoice_id = $invoice->id;
              $item->product_name = $name;
              $item->price = $request->price[$i];
              $item->quantity = $request->quantity[$i];
              $item->sub_total = $item->quantity * $item->price;
              $item->save();
              $i++;
              $sub_total_amount += $item->sub_total;
            }
            $invoice->sub_total_amount =  $sub_total_amount;
            $invoice->total_amount = ($invoice->sub_total_amount - $invoice->discount_amount);
            $invoice->save();

            DB::commit();
            alert()->success('Success','Invoice is updated');
        // }catch (Exception $ex){
        //     DB::rollBack();
        //     alert()->warning('Problem','Here are some issues, please try again');
        // }
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }


    public function appendTd()
    {
        $row = session()->get('tr_row')+1;
        session()->put('tr_row', $row);
        $row = session()->get('tr_row');
        $view = view('invoice.render_td',compact('row'));
        return response()->json(['view'=>$view->render()],200); 
    }

    public function print($id)
    {

        $invoice = Invoice::where('id',$id)->with('items')->first();
        return view('invoice.print',compact('invoice'));
    }


    public function pdf($id)
    {
      
      // share data to view
      $invoice = Invoice::where('id',$id)->with('items')->first();
      $pdf = PDF::loadView('invoice.pdf', compact('invoice'));
      
      return $pdf->download('pdf_file.pdf');
    }
}
