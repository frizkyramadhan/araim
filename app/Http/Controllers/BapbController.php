<?php

namespace App\Http\Controllers;

use App\Models\Bapb;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use App\Mail\BapbNotificationMail;

class BapbController extends Controller
{
    public function __construct()
    {
        $this->middleware('check_role:admin');
    }

    public function index()
    {
        $title = 'BAPB';
        $subtitle = 'Berita Acara Peminjaman Barang';
        $bapbs = DB::table('bapbs')
            ->leftJoin('employees as submit', 'bapbs.bapb_submit', '=', 'submit.id')
            ->leftJoin('employees as receive', 'bapbs.bapb_receive', '=', 'receive.id')
            ->select('bapbs.bapb_no', 'bapbs.bapb_reg', 'bapb_date', 'receive.fullname as receive_name', 'submit.fullname as submit_name')
            ->distinct()->orderBy('bapb_no', 'desc')->get();
        // dd($bapbs);
        return view('bapbs.index', compact('title', 'subtitle', 'bapbs'));
    }

    public function getInventories(Request $request)
    {
        if ($request->ajax()) {
            // get inevntories based on employee id get from request
            $employee_id = $request->get('employee_id');
            $inventories = DB::table('inventories')
                ->leftJoin('employees', 'inventories.employee_id', '=', 'employees.id')
                ->leftJoin('assets', 'inventories.asset_id', '=', 'assets.id')
                ->leftJoin('brands', 'inventories.brand_id', '=', 'brands.id')
                ->leftJoin('projects', 'inventories.project_id', '=', 'projects.id')
                ->select('inventories.*', 'employees.nik', 'employees.fullname', 'assets.asset_name', 'brands.brand_name', 'projects.project_code')
                ->where('employees.id', $employee_id)
                ->orderBy('inventories.id', 'desc')
                ->get();

            return $inventories;
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $title = 'BAPB';
        $subtitle = 'Berita Acara Peminjaman Barang';
        $submits = DB::table('employees')
            ->join('positions', 'employees.position_id', '=', 'positions.id')
            ->join('departments', 'positions.department_id', '=', 'departments.id')
            ->select('employees.*', 'departments.dept_name')
            ->where('departments.dept_name', '=', 'Information Technology')
            ->where('status', '1')->orderBy('fullname', 'asc')->get();
        $receives = Employee::where('status', '1')->orderBy('fullname', 'asc')->get();

        // generate inventory no
        $year = date('Y');
        $month = date('m');
        $number = Bapb::max('bapb_no') + 1;
        $bapb_no = str_pad($number, 6, '0', STR_PAD_LEFT);

        // Handle inventory_id from query string
        $selectedInventoryId = $request->get('inventory_id');
        $selectedEmployeeId = null;

        if ($selectedInventoryId) {
            $inventory = DB::table('inventories')->where('id', $selectedInventoryId)->first();
            if ($inventory) {
                $selectedEmployeeId = $inventory->employee_id;
            }
        }

        return view('bapbs.create', compact('title', 'subtitle', 'submits', 'receives', 'bapb_no', 'year', 'month', 'selectedEmployeeId', 'selectedInventoryId'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // validate
        $request->validate([
            'bapb_no' => 'required',
            'bapb_reg' => 'required',
            'bapb_date' => 'required',
            'bapb_submit' => 'required',
            'bapb_receive' => 'required',
            'duration' => 'required',
            'inventory_id' => 'required',
        ]);

        $data = $request->all();
        foreach ($data['inventory_id'] as $inventory => $value) {
            $inventories = array(
                'bapb_no' => $data['bapb_no'],
                'bapb_reg' => $data['bapb_reg'],
                'bapb_date' => $data['bapb_date'],
                'bapb_submit' => $data['bapb_submit'],
                'bapb_receive' => $data['bapb_receive'],
                'duration' => $data['duration'],
                'inventory_id' => $data['inventory_id'][$inventory],
            );
            // dd($inventories);
            Bapb::create($inventories);
        }

        return redirect()->route('bapbs.index')->with('success', 'Data berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Bapb  $bapb
     * @return \Illuminate\Http\Response
     */
    public function show($bapb_no)
    {
        $title = 'BAPB';
        $subtitle = 'Berita Acara Peminjaman Barang';
        $bapb = DB::table('bapbs')
            ->leftJoin('employees as submit', 'bapbs.bapb_submit', '=', 'submit.id')
            ->leftJoin('positions as pos_submit', 'submit.position_id', '=', 'pos_submit.id')
            ->leftJoin('employees as receive', 'bapbs.bapb_receive', '=', 'receive.id')
            ->leftJoin('positions as pos_receive', 'receive.position_id', '=', 'pos_receive.id')
            ->select('bapbs.*', 'bapbs.signed_document', 'receive.fullname as receive_name', 'receive.nik as receive_nik', 'pos_receive.position_name as receive_pos', 'submit.fullname as submit_name', 'submit.nik as submit_nik', 'pos_submit.position_name as submit_pos')
            ->where('bapb_no', '=', $bapb_no)
            ->orderBy('bapb_no', 'desc')->first();
        $bapb_row = DB::table('bapbs')
            ->leftJoin('inventories', 'bapbs.inventory_id', '=', 'inventories.id')
            ->leftJoin('assets', 'inventories.asset_id', '=', 'assets.id')
            ->leftJoin('brands', 'inventories.brand_id', '=', 'brands.id')
            ->select('bapbs.bapb_no', 'inventories.*', 'assets.asset_name', 'brands.brand_name')
            ->where('bapb_no', '=', $bapb_no)
            ->get();
        // dd($bapb, $bapb_row);
        return view('bapbs.show', compact('title', 'subtitle', 'bapb', 'bapb_row'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Bapb  $bapb
     * @return \Illuminate\Http\Response
     */
    public function edit($bapb_no)
    {
        $title = 'BAPB';
        $subtitle = 'Berita Acara Peminjaman Barang';

        $submits = DB::table('employees')
            ->join('positions', 'employees.position_id', '=', 'positions.id')
            ->join('departments', 'positions.department_id', '=', 'departments.id')
            ->select('employees.*', 'departments.dept_name')
            ->where('departments.dept_name', '=', 'Information Technology')
            ->where('status', '1')->orderBy('fullname', 'asc')->get();
        $receives = Employee::where('status', '1')->orderBy('fullname', 'asc')->get();

        $bapb = DB::table('bapbs')
            ->leftJoin('employees as submit', 'bapbs.bapb_submit', '=', 'submit.id')
            ->leftJoin('positions as pos_submit', 'submit.position_id', '=', 'pos_submit.id')
            ->leftJoin('employees as receive', 'bapbs.bapb_receive', '=', 'receive.id')
            ->leftJoin('positions as pos_receive', 'receive.position_id', '=', 'pos_receive.id')
            ->select('bapbs.*', 'receive.fullname as receive_name', 'receive.nik as receive_nik', 'pos_receive.position_name as receive_pos', 'submit.fullname as submit_name', 'submit.nik as submit_nik', 'pos_submit.position_name as submit_pos')
            ->where('bapb_no', '=', $bapb_no)
            ->orderBy('bapb_no', 'desc')->first();

        if (empty($bapb)) {
            return redirect('bapbs');
        }

        $inventories = DB::table('inventories')
            ->leftJoin('assets', 'inventories.asset_id', '=', 'assets.id')
            ->leftJoin('brands', 'inventories.brand_id', '=', 'brands.id')
            ->leftJoin('employees', 'inventories.employee_id', '=', 'employees.id')
            ->leftJoin('bapbs', 'bapbs.inventory_id', '=', 'inventories.id')
            ->leftJoin('projects', 'inventories.project_id', '=', 'projects.id')
            ->select('inventories.*', 'assets.asset_name', 'bapbs.inventory_id', 'brands.brand_name', 'projects.project_code')
            ->where('employees.id', '=', $bapb->bapb_receive)
            ->orderBy('inventories.id', 'desc')
            ->distinct()->get();

        $bapb_row = DB::table('inventories')
            ->leftJoin('assets', 'inventories.asset_id', '=', 'assets.id')
            ->leftJoin('brands', 'inventories.brand_id', '=', 'brands.id')
            ->leftJoin('employees', 'inventories.employee_id', '=', 'employees.id')
            ->leftJoin('bapbs', 'bapbs.inventory_id', '=', 'inventories.id')
            ->leftJoin('projects', 'inventories.project_id', '=', 'projects.id')
            ->select('inventories.*', 'assets.asset_name', 'bapbs.inventory_id', 'bapbs.bapb_no', 'bapbs.id as bapb_id', 'brands.brand_name', 'projects.project_code')
            ->where('employees.id', '=', $bapb->bapb_receive)
            ->where('bapbs.bapb_no', '=', $bapb_no)
            ->orderBy('inventories.id', 'desc')
            ->distinct()
            ->get();

        // dd($bapb_row);

        return view('bapbs.edit', compact('title', 'subtitle', 'bapb', 'receives', 'bapb_no', 'submits', 'inventories', 'bapb_row'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Bapb  $bapb
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $bapb_no)
    {
        $bapb_detail = DB::table('bapbs')->where('bapb_no', $bapb_no)->get();
        foreach ($bapb_detail as $detail) {
            if ($request->has('deleteRow' . $detail->id)) {
                DB::table('bapbs')->where('id', '=', $detail->id)->delete();
                return redirect('bapbs/' . $bapb_no . '/edit')->with('success', 'Item deleted successfully');
            }
        }

        $request->validate([
            'bapb_no' => 'required',
            'bapb_reg' => 'required',
            'bapb_date' => 'required',
            'bapb_submit' => 'required',
            'bapb_receive' => 'required',
            'duration' => 'required',
        ]);

        $data = $request->all();
        if (!empty($request->inventory_id)) {
            foreach ($data['inventory_id'] as $inventory => $value) {
                DB::table('bapbs')->where('bapb_no', $bapb_no)->update([
                    'bapb_no' => $data['bapb_no'],
                    'bapb_reg' => $data['bapb_reg'],
                    'bapb_date' => $data['bapb_date'],
                    'bapb_submit' => $data['bapb_submit'],
                    'bapb_receive' => $data['bapb_receive'],
                    'duration' => $data['duration']
                ]);
                $inventories = array(
                    'bapb_no' => $data['bapb_no'],
                    'bapb_reg' => $data['bapb_reg'],
                    'bapb_date' => $data['bapb_date'],
                    'bapb_submit' => $data['bapb_submit'],
                    'bapb_receive' => $data['bapb_receive'],
                    'duration' => $data['duration'],
                    'inventory_id' => $data['inventory_id'][$inventory],
                );
                // dd($inventories);
                Bapb::create($inventories);
                return redirect('bapbs/' . $bapb_no)->with('success', 'BAPB updated successfully');
            }
        } else {
            DB::table('bapbs')->where('bapb_no', $bapb_no)->update([
                'bapb_no' => $data['bapb_no'],
                'bapb_reg' => $data['bapb_reg'],
                'bapb_date' => $data['bapb_date'],
                'bapb_submit' => $data['bapb_submit'],
                'bapb_receive' => $data['bapb_receive'],
                'duration' => $data['duration']
            ]);
            return redirect('bapbs/' . $bapb_no)->with('success', 'BAPB updated successfully');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Bapb  $bapb
     * @return \Illuminate\Http\Response
     */
    public function destroy($bapb_no)
    {
        DB::table('bapbs')->where('bapb_no', $bapb_no)->delete();
        return back()->with('success', 'BAPB deleted successfully');
    }

    public function print($bapb_no)
    {
        $title = 'BAPB';
        $subtitle = 'Berita Acara Peminjaman Barang';
        $bapb = DB::table('bapbs')
            ->leftJoin('employees as submit', 'bapbs.bapb_submit', '=', 'submit.id')
            ->leftJoin('positions as pos_submit', 'submit.position_id', '=', 'pos_submit.id')
            ->leftJoin('employees as receive', 'bapbs.bapb_receive', '=', 'receive.id')
            ->leftJoin('positions as pos_receive', 'receive.position_id', '=', 'pos_receive.id')
            ->select('bapbs.*', 'bapbs.signed_document', 'receive.fullname as receive_name', 'receive.nik as receive_nik', 'pos_receive.position_name as receive_pos', 'submit.fullname as submit_name', 'submit.nik as submit_nik', 'pos_submit.position_name as submit_pos')
            ->where('bapb_no', '=', $bapb_no)
            ->orderBy('bapb_no', 'desc')->first();
        $bapb_row = DB::table('bapbs')
            ->leftJoin('inventories', 'bapbs.inventory_id', '=', 'inventories.id')
            ->leftJoin('assets', 'inventories.asset_id', '=', 'assets.id')
            ->leftJoin('brands', 'inventories.brand_id', '=', 'brands.id')
            ->select('bapbs.bapb_no', 'inventories.*', 'assets.asset_name', 'brands.brand_name')
            ->where('bapb_no', '=', $bapb_no)
            ->get();
        // dd($bapb, $bapb_row);
        return view('bapbs.print', compact('title', 'subtitle', 'bapb', 'bapb_row'));
    }

    public function uploadDocument(Request $request, $bapb_no)
    {
        $request->validate([
            'signed_document' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120', // max 5MB
        ]);

        // Get existing document path to delete old file
        $existingBapb = DB::table('bapbs')->where('bapb_no', $bapb_no)->first();
        if ($existingBapb && $existingBapb->signed_document) {
            Storage::disk('public')->delete($existingBapb->signed_document);
        }

        // Handle file upload
        $file = $request->file('signed_document');
        $fileName = 'bapb_' . $bapb_no . '_' . time() . '.' . $file->getClientOriginalExtension();
        $signedDocumentPath = $file->storeAs('bapbs/signed_documents', $fileName, 'public');

        // Update all records with same bapb_no
        DB::table('bapbs')->where('bapb_no', $bapb_no)->update([
            'signed_document' => $signedDocumentPath
        ]);

        return redirect('bapbs/' . $bapb_no)->with('success', 'Dokumen berhasil diupload');
    }

    public function deleteDocument($bapb_no)
    {
        // Get existing document path to delete file
        $existingBapb = DB::table('bapbs')->where('bapb_no', $bapb_no)->first();

        if ($existingBapb && $existingBapb->signed_document) {
            // Delete file from storage
            Storage::disk('public')->delete($existingBapb->signed_document);

            // Update all records with same bapb_no to remove document path
            DB::table('bapbs')->where('bapb_no', $bapb_no)->update([
                'signed_document' => null
            ]);

            return redirect('bapbs/' . $bapb_no)->with('success', 'Dokumen berhasil dihapus');
        }

        return redirect('bapbs/' . $bapb_no)->with('error', 'Dokumen tidak ditemukan');
    }

    public function previewEmail($bapb_no)
    {
        // Get BAPB data
        $bapb = DB::table('bapbs')
            ->leftJoin('employees as submit', 'bapbs.bapb_submit', '=', 'submit.id')
            ->leftJoin('positions as pos_submit', 'submit.position_id', '=', 'pos_submit.id')
            ->leftJoin('employees as receive', 'bapbs.bapb_receive', '=', 'receive.id')
            ->leftJoin('positions as pos_receive', 'receive.position_id', '=', 'pos_receive.id')
            ->select('bapbs.bapb_no', 'bapbs.bapb_reg', 'bapbs.signed_document', 'bapb_date', 'bapbs.duration', 'receive.fullname as receive_name', 'receive.nik as receive_nik', 'pos_receive.position_name as receive_pos', 'submit.fullname as submit_name', 'submit.nik as submit_nik', 'pos_submit.position_name as submit_pos')
            ->where('bapb_no', '=', $bapb_no)
            ->orderBy('bapb_no', 'desc')->first();

        $bapbRow = DB::table('bapbs')
            ->leftJoin('inventories', 'bapbs.inventory_id', '=', 'inventories.id')
            ->leftJoin('assets', 'inventories.asset_id', '=', 'assets.id')
            ->leftJoin('brands', 'inventories.brand_id', '=', 'brands.id')
            ->select('bapbs.bapb_no', 'inventories.*', 'assets.asset_name', 'brands.brand_name')
            ->where('bapb_no', '=', $bapb_no)
            ->get();

        if (empty($bapb)) {
            return redirect('bapbs')->with('error', 'BAPB not found');
        }

        return view('emails.bapb_notification', compact('bapb', 'bapbRow'));
    }

    public function sendEmail(Request $request, $bapb_no)
    {
        $request->validate([
            'mail_to' => 'required|string',
            'mail_cc' => 'nullable|string',
        ]);

        // Parse email addresses for TO (comma or newline separated)
        $mailTo = array_filter(array_map('trim', preg_split('/[,\n\r]+/', $request->mail_to)));

        if (empty($mailTo)) {
            return redirect('bapbs/' . $bapb_no)->with('error', 'Please provide at least one email address in Mail To');
        }

        // Parse email addresses for CC (comma or newline separated) - optional
        $mailCc = [];
        if (!empty($request->mail_cc)) {
            $mailCc = array_filter(array_map('trim', preg_split('/[,\n\r]+/', $request->mail_cc)));
        }

        // Validate email addresses for TO
        $validToEmails = [];
        foreach ($mailTo as $email) {
            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $validToEmails[] = $email;
            }
        }

        if (empty($validToEmails)) {
            return redirect('bapbs/' . $bapb_no)->with('error', 'No valid email addresses found in Mail To');
        }

        // Validate email addresses for CC
        $validCcEmails = [];
        foreach ($mailCc as $email) {
            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $validCcEmails[] = $email;
            }
        }

        // Get BAPB data
        $bapb = DB::table('bapbs')
            ->leftJoin('employees as submit', 'bapbs.bapb_submit', '=', 'submit.id')
            ->leftJoin('positions as pos_submit', 'submit.position_id', '=', 'pos_submit.id')
            ->leftJoin('employees as receive', 'bapbs.bapb_receive', '=', 'receive.id')
            ->leftJoin('positions as pos_receive', 'receive.position_id', '=', 'pos_receive.id')
            ->select('bapbs.bapb_no', 'bapbs.bapb_reg', 'bapbs.signed_document', 'bapb_date', 'bapbs.duration', 'receive.fullname as receive_name', 'receive.nik as receive_nik', 'pos_receive.position_name as receive_pos', 'submit.fullname as submit_name', 'submit.nik as submit_nik', 'pos_submit.position_name as submit_pos')
            ->where('bapb_no', '=', $bapb_no)
            ->orderBy('bapb_no', 'desc')->first();

        $bapb_row = DB::table('bapbs')
            ->leftJoin('inventories', 'bapbs.inventory_id', '=', 'inventories.id')
            ->leftJoin('assets', 'inventories.asset_id', '=', 'assets.id')
            ->leftJoin('brands', 'inventories.brand_id', '=', 'brands.id')
            ->select('bapbs.bapb_no', 'inventories.*', 'assets.asset_name', 'brands.brand_name')
            ->where('bapb_no', '=', $bapb_no)
            ->get();

        if (empty($bapb)) {
            return redirect('bapbs/' . $bapb_no)->with('error', 'BAPB not found');
        }

        try {
            // Create mailable instance
            $mailable = new BapbNotificationMail($bapb, $bapb_row);

            // Send email with TO and CC recipients
            $mail = Mail::to($validToEmails);

            // Add CC recipients if provided
            if (!empty($validCcEmails)) {
                $mail->cc($validCcEmails);
            }

            // Send the email
            $mail->send($mailable);

            $toCount = count($validToEmails);
            $ccCount = count($validCcEmails);
            $message = "Email notification sent successfully to {$toCount} recipient(s)";
            if ($ccCount > 0) {
                $message .= " and {$ccCount} CC recipient(s)";
            }

            return redirect('bapbs/' . $bapb_no)->with('success', $message);
        } catch (\Exception $e) {
            return redirect('bapbs/' . $bapb_no)->with('error', 'Failed to send email: ' . $e->getMessage());
        }
    }
}
