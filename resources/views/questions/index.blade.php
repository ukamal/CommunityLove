@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <h3>All Questions</h3>
                            <h3 class="ml-auto">
                                <a class="btn btn-outline-secondary" href="{{route('questions.create')}}">Ask Question</a>
                            </h3>
                        </div>
                    </div>
                    @include('layouts._message')
                    @foreach($questions as $question)
                        <div class="card-body">
                            <div class="media">
                                <div class="d-flex flex-column counters">
                                    <div class="vote">
                                       <strong>
                                           {{$question->votes_count}}
                                       </strong>
                                        {{str_plural('vote',$question->votes_count)}}
                                    </div>

                                    <div class="status answered-accepted">
                                        <strong>
                                            {{$question->answers_count}}
                                        </strong>
                                        {{str_plural('answer',$question->answers_count)}}
                                    </div>

                                    <div class="views">
                                            {{$question->views}}
                                        {{str_plural('views',$question->views)}}
                                    </div>
                                </div>
                                <div class="media-body">
                                    <div class="d-flex align-items-center">
                                        <h3 class="mt-0">
                                            <a href="{{$question->url}}">
                                                {{$question->title}}
                                            </a>
                                        </h3>
                                        <div class="ml-auto">
                                            @can('update', $question)
                                            <a class="btn btn-outline-primary btn-sm" href="{{route('questions.edit', $question->id)}}">
                                                Edit
                                            </a>
                                            @endcan

                                            @can('delete', $question)
                                            <form class="form-delete" method="post" action="{{route('questions.destroy', $question->id)}}">
                                                @method('DELETE')
                                                @csrf
                                            <button onclick="return confirm('Are you sure?')" class="btn btn-outline-danger btn-sm" type="submit">Delete</button>
                                            </form>
                                            @endcan
                                        </div>
                                    </div>
                                        <p class="lead">
                                            Asked By
                                            <a href="{{$question->user->url}}">
                                                {{$question->user->name}}
                                            </a>
                                            <small class="text-muted">
                                                {{$question->create_date}}
                                            </small>
                                        </p>
                                    {{str_limit(strip_tags($question->body,250))}}
                                </div>
                            </div>
                        </div>
                        <hr>
                    @endforeach

{{--                    <div class="mx-auto">--}}
{{--                    {{$questions->links()}}--}}
{{--                    </div>--}}

                    {{$questions->links()}}

                </div>
            </div>
        </div>
    </div>
@endsection
