@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <h3>Edit Answer</h3>
                            <h3 class="ml-auto">
                                <a class="btn btn-outline-secondary" href="{{route('questions.index')}}">Back to Question</a>
                            </h3>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{route('questions.answers.destroy',[$question->id,$answer->id])}}" method="post">

                            @csrf
                            @method('PUT')
                            <div class="form-group">
                                <label for="question-body">Update Your Answer </label>
                                <textarea class="form-control {{ $errors->has('body') ? ' is-invalid' : '' }}" name="body" id="" cols="30" rows="10">
                            {{old('body',$answer->body)}}
                        </textarea>
                                @if ($errors->has('body'))
                                    <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('body') }}</strong>
                            </span>
                                @endif
                            </div>
                            <button class="btn btn-outline-info btn-lg" type="submit">Update This Answer</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
@endsection
