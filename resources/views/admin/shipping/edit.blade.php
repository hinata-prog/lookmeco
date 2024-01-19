@extends('admin.layouts.app')

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid my-2">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Edit Shipping</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{ route('shipping.create') }}" class="btn btn-primary">Back</a>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
</section>
<!-- Main content -->
<section class="content">
    <!-- Default box -->
    <div class="container-fluid">
        @include('admin.message')
        <form action="" method="post" id="shippingForm" name="shippingForm">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="province_id">Name</label>
                                <select name="province_id" id="province_id" class="form-control">
                                    <option value="">Select a everywhere</option>
                                    <option value="everywhere" {{ ($shippingCharge->province_id == 'everywhere') ? 'selected' : '' }}>Everywhere other</option>

                                    @if($provinces->isNotEmpty())
                                    @foreach ($provinces as $province)
                                        <option value="{{ $province->id }}"  {{ ($shippingCharge->province_id == $province->id) ? 'selected' : '' }}>{{ $province->name }}</option>
                                    @endforeach
                                    @endif
                                </select>
                                <p></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="amount">Amount</label>
                                <input type="text" name="amount" id="amount" class="form-control" placeholder="Amount" value="{{ $shippingCharge->amount }}">
                                <p></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="pb-5 pt-3">
                <button type="submit" class="btn btn-primary">Create</button>
                <a href="{{ route("shipping.create") }}" class="btn btn-outline-dark ml-3">Cancel</a>
            </div>

        </form>
    </div>
    <!-- /.card -->
</section>
<!-- /.content -->

@endsection

@section('customJs')
<script>
$("#shippingForm").submit(function(event){
    event.preventDefault();
    var element = $(this);
    $("button[type=submit]").prop('disabled',true);

    $.ajax({
        url:'{{ route('shipping.update',$shippingCharge->id) }}',
        type:'put',
        data: element.serializeArray(),
        dataType: 'json',
        success: function(response){
            $("button[type=submit]").prop('disabled',false);

            if(response.status == true) {

                window.location.href="{{ route('shipping.create') }}";

                $("#province_id").removeClass('is-invalid')
                .siblings('p')
                .removeClass('invalid-feedback')
                .html("");

                $("#amount").removeClass('is-invalid')
                .siblings('p')
                .removeClass('invalid-feedback')
                .html("");
            }else{

                var errors=response.errors;
                if(errors['province_id']){
                    $("#province_id").addClass('is-invalid')
                    .siblings('p')
                    .addClass('invalid-feedback')
                    .html(errors['province_id']);
                }else{
                    $("#province_id").removeClass('is-invalid')
                    .siblings('p')
                    .removeClass('invalid-feedback')
                    .html("");
                }


                if(errors['amount']){
                    $("#amount").addClass('is-invalid')
                    .siblings('p')
                    .addClass('invalid-feedback')
                    .html(errors['amount']);
                }else{
                    $("#amount").removeClass('is-invalid')
                    .siblings('p')
                    .removeClass('invalid-feedback')
                    .html("");
                }
            }
        },
        error: function(jqXHR, exception){
            console.log("Something went wrong!");
        }
    })
});


</script>
@endsection
