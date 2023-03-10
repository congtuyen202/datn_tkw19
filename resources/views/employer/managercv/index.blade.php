@php
    use Carbon\Carbon;
@endphp
@extends('layouts.admin')
@section('content')
    <div class="container">
        <div class="fade-in">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <header class="header header-sticky">
                                <div class="container-fluid">
                                    <label class=" px-md-0 me-md-3">Quản lý CV</label>
                                    <ul class="header-nav ms-3 d-flex">
                                        <form action="{{ route('employer.quan-ly-cv.index') }}" class="d-flex"
                                            method="get">
                                            <input name="free_word" class="custom-input" placeholder="Tìm Kiếm...."
                                                value="" autocomplete="off" id="free_word">
                                            <button class="nav-link py-0 btn-next-step"
                                                href="{{ route('employer.quan-ly-cv.create') }}">
                                                <i class="fa fa-search"></i>
                                            </button>
                                        </form>
                                        <a class="nav-link py-0 btn-next-step"
                                            href="{{ route('employer.quan-ly-cv.create') }}">
                                            Thêm tin
                                        </a>
                                    </ul>
                                </div>
                            </header>
                        </div>
                        <div class="card-body">
                            <div class="row gy-3">
                                <table class="table table-striped table-hover table-bordered text-center">
                                    <thead>
                                        <th scope="col">Hình ảnh</th>
                                        <th scope="col">Họ và Tên</th>
                                        <th scope="col">Ứng tuyển vị trí</th>
                                        <th scope="col">Chuyên ngành</th>
                                        <th scope="col">Kỹ năng</th>
                                        <th scope="col">Ngày nộp đơn</th>
                                        <th scope="col">trạng thái</th>
                                        <th scope="col">thao tác</th>
                                    </thead>
                                    <tbody>
                                        @foreach ($cv as $item)
                                            <tr>
                                                <td><img src="{{ $item->images }}" alt="" width="150"
                                                        height="150"></td>
                                                <td>{{ $item->user_name }}</td>
                                                <td>{{ $item->profession_name }}</td>
                                                <td> {{ $item->majors_name }} </td>
                                                <td>
                                                    @foreach ($item->getskill as $value)
                                                        {{ $value->name }}
                                                    @endforeach
                                                </td>
                                                <td>{{ Carbon::parse($item->create_at_sv)->format('d-m-Y') }}</td>
                                                <td>{{ $item->status == 0 ? 'chưa xem' : 'đã xem' }}</td>
                                                <td>
                                                    <div class="dropdown">
                                                        <button class="btn btn-warning btn-radius-auto dropdown-toggle"
                                                            id="action" type="button" data-coreui-toggle="dropdown"
                                                            aria-expanded="false">Chức năng</button>
                                                        <ul class="dropdown-menu" aria-labelledby="action">
                                                            <li>
                                                                <a class="dropdown-item" href=""
                                                                    class="dropdown-item">
                                                                    <i class="fa fa-eye"></i>Tải xuông
                                                                </a>
                                                            </li>
                                                            <li class="dropdown-divider"></li>
                                                            <li>
                                                                <a class="dropdown-item" href="{{ asset($item->file_cv) }}"
                                                                    target="_blank" class="dropdown-item">
                                                                    <i class="fa fa-eye"></i>Xem cv
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="group-paginate">
                                {{-- {{ $news->appends(SearchQueryComponent::alterQuery($request))->links('pagination.admin') }} --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
