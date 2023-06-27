

@section('title', 'Add Excel File')
@extends('layouts.master_dashboard')

@section('content')

<div class="content-wrapper">
    <div class="content-header row">
        
    </div>
    <div class="content-body">
        <section id="multiple-column-form">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Import Excel File</h4>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-danger" style="display: none;"><strong>Sorry: </strong><span class="error-message"></span></div>
                            <div class="alert alert-success" style="display: none;"><strong>Success: </strong><span class="success-message"></span></div>
                                <form class="form" id="import_excel_file" enctype="multipart/form-data">
                                   
                                    @csrf                                                           
                                    <input type="hidden" name="csv_data_file_id" value="">
                                <div class="row">
                                    <div class="col-md-6 col-12 image_div mb-1">
                                        <label for="csv_file">Import File</label>
                                        <div class="input-group">
                                            <input type="file" name="csv_file"  id="csv_file" data-img-val="" class="form-control @error('csv_file') is-invalid @enderror" placeholder="Excel import file" required>
                                            @error('csv_file')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="table_name">Database Tables</label>
                                            <select class="form-control @error('table_name') is-invalid @enderror" name="table_name" id="table_name" required>
                                                <option value="">Choose Tabel Name</option>
                                                    @if(isset($table_name['table_name']))
                                                        @foreach ($table_name['table_name'] as $key=>$db_field)
                                                            <option value="{{ $db_field->table_name }}">{{ $db_field->table_name }}</option>
                                                        @endforeach
                                                    @endif
                                            </select>
                                            @error('table_name')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <button type="submit" class="btn btn-primary mr-1 waves-effect waves-float waves-light add_excel_file">Add File</button>
                                    </div>
                                </div>
                            </form>
                            
                            <div class="table-responsive">
                                
                                <form id="import_form_submit" enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" id="csv_import_data_id" name="csv_import_data_id">
                                    <div class="table-responsive">
                                        <table class="table form_text">
                                            <div class="row mt-5">
                                                <div class="col-12">
                                                    <button  type="submit" id="import_btn" class="btn btn-primary import_data hidden" >Import File</button>
                                                </div>
                                            </div>
                                        </table>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
@endsection
