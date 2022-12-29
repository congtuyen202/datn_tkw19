<?php

namespace App\Http\Controllers\Seeker;

use App\Enums\StatusCode;
use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Models\ProfileUserCv;
use App\Models\UploadCv;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class ManageUploadController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public UploadCv $upload;
    public ProfileUserCv $profileUserCv;
    public function __construct(UploadCv $upload, ProfileUserCv $profileUserCv)
    {
        $this->upload = $upload;
        $this->profileUserCv = $profileUserCv;
    }
    public function index()
    {
        $breadcrumbs = [
            'Quản lý cv'
        ];
        $cv = $this->upload->get();
        return view('client.seeker.save-cv', [
            'breadcrumbs' => $breadcrumbs,
            'cv' => $cv,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $breadcrumbs = [
            [
                'url' => route('quan-ly-cv.index'),
                'name' => 'Quản lý cv'
            ],
            'Thêm CV'
        ];
        return view('client.seeker.create-cv', [
            'breadcrumbs' => $breadcrumbs,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request)
    {
        try {
            $upload = new $this->upload();
            $upload->user_id = Auth::guard('user')->user()->id;
            $upload->title = 'Triệu Việt Đức';
            if ($request->hasFile('file_cv')) {
                $upload->file_cv = $request->file_cv->storeAs('images/cv', $request->file_cv->hashName());
            }
            $upload->save();
            $this->setFlash(__('Thêm cv mới thành công'));
            return redirect()->route('quan-ly-cv.index');
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->setFlash(__('đã có một lỗi không rõ nguyên nhân xảy ra'), 'error');
            return redirect()->route('quan-ly-cv.index');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $this->upload->destroy($id);
        $this->setFlash(__('Xóa cv thành công'));
        return redirect()->route('quan-ly-cv.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    public function createFormCV()
    {
        return view('client.seeker.create_form_cv');
    }
    public function storeFormCV(Request $request)
    {
        try {


            $profileUserCv = new $this->profileUserCv();
            $profileUserCv->email = $request['data']['email'];
            $profileUserCv->address = $request['data']['address'];
            $profileUserCv->phone = $request['data']['phone'];
            $profileUserCv->skill = $request['data']['skill'];
            $profileUserCv->certificate = $request['data']['certificate'];
            $profileUserCv->target = $request['data']['target'];
            $profileUserCv->work = $request['data']['work'];
            $profileUserCv->work_detail = $request['data']['work_detail'];
            $profileUserCv->project = $request['data']['project'];
            $profileUserCv->project_detail = $request['data']['project_detail'];


            $profileUserCv->save();
            //create to jobskill

            return response()->json([
                'message' => 'Cập nhật thành công',
                'status' => StatusCode::OK
            ], StatusCode::OK);
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json([
                'message' => 'Đã có một lỗi xảy ra',
                'status' => StatusCode::FORBIDDEN,
            ], StatusCode::OK);
        }
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->upload->destroy($id);
        $this->setFlash(__('Xóa cv thành công'));
        return redirect()->route('quan-ly-cv.index');
    }

    // tao va tai xuomh cv
    public function downloadPdf()
    {
        $pdf = PDF::loadView('client.seeker.pdf')->setOptions(['defaultFont' => 'sans-serif']);
        return $pdf->stream();
    }
}
