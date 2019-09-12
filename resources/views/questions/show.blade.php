@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                  <div class="card-body">
                      <div class="card-title">
                          <div class="d-flex align-items-center">
                              <h3>{{$question->title}}</h3>
                              <h3 class="ml-auto">
                                  <a class="btn btn-outline-secondary"
                                     href="{{route('questions.index')}}">Back to Question</a>
                              </h3>
                          </div>
                      </div>
                      <hr>
                      <div class="media">
                          <div class="d-flex flex-column votes-control">
                              <a class="vote-up
                              {{Auth::guest() ? 'off' : ''}}"
                                 onclick="event.preventDefault();
                                         document.getElementById('questions-vote-up{{$question->id}}').submit()"
                                 href="">
                                 <i class="fas fa-caret-up fa-3x"></i>
                              </a>
                              <span class="votes-count">
                                  {{$question->votes_count}}
                              </span>
                              <form id="questions-vote-up{{$question->id}}"
                                    style="display: none" action="/questions/{{$question->id}}/vote" method="post">
                                @csrf
                                  <input type="hidden" name="vote" value="1">
                              </form>
                              <a class="vote-down
                                {{Auth::guest() ? 'off' : ''}}"
                                 onclick="event.preventDefault();
                                         document.getElementById('questions-vote-down{{$question->id}}').submit()"
                                 href="">
                                  <i class="fas fa-caret-down fa-3x"></i>
                              </a>
                              <form id="questions-vote-down{{$question->id}}"
                                    style="display: none" action="/questions/{{$question->id}}/vote" method="post">
                                  @csrf
                                  <input type="hidden" name="vote" value="-1">
                              </form>
                              <favorite :question="{{ $question }}">

                              </favorite>
                                <form id="questions-favorites-{{$question->id}}"
                                    style="display: none" action="/questions/{{$question->id}}/favorites" method="post">
                                  @csrf
                                  @if($question->is_favorited)
                                    @method('DELETE')
                                      @endif
                              </form>
                          </div>
                          <div class="media-body">
                              {!! $question->body_html !!}
                              <div class="float-right">
                                   <!----------vuejs------->
                                  <user-info :model="{{$question}}" label="Asked"></user-info>
                              </div>
                          </div>
                      </div>
                  </div>
                </div>
            </div>
        </div>
        <!--------Answer-------->
        <div class="row mt-5" v-cloak>
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">
                            <h2>Your Answer</h2>
                            @include('layouts._message')
                            <form action="{{route('questions.answers.store',$question->id)}}" method="post">
                                @csrf
                                <div class="form-group">
                                    <textarea rows="7" class="form-control {{ $errors->has('body') ? ' is-invalid' : '' }}" name="body"></textarea>
                                    @if ($errors->has('body'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('body') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <button class="btn btn-outline-primary" type="submit">Submit</button>
                                </div>
                            </form>
                            <hr>
                            <h3>
                                {{$question->answers_count}}
                                {{str_plural('Answer',$question->answers_count)}}
                            </h3>
                        </div>
                        <hr>
                        @foreach($question->answers as $answer)
                            <div class="media">
                                <div class="d-flex flex-column votes-control">
                                    <a class="vote-up
                              {{Auth::guest() ? 'off' : ''}}"
                                       onclick="event.preventDefault();
                                               document.getElementById('answers-vote-up{{$answer->id}}').submit()"
                                       href="">
                                        <i class="fas fa-caret-up fa-3x"></i>
                                    </a>
                                    <span class="votes-count">
                                  {{$answer->votes_count}}
                              </span>
                                    <form id="answers-vote-up{{$answer->id}}"
                                          style="display: none" action="/answers/{{$answer->id}}/vote" method="post">
                                        @csrf
                                        <input type="hidden" name="vote" value="1">
                                    </form>
                                    <a class="vote-down
                                {{Auth::guest() ? 'off' : ''}}"
                                       onclick="event.preventDefault();
                                               document.getElementById('answers-vote-down{{$answer->id}}').submit()"
                                       href="">
                                        <i class="fas fa-caret-down fa-3x"></i>
                                    </a>
                                    <form id="answers-vote-down{{$answer->id}}"
                                          style="display: none" action="/answers/{{$answer->id}}/vote" method="post">
                                        @csrf
                                        <input type="hidden" name="vote" value="-1">
                                    </form>
                                    @can('accept', $answer)
                                    <a class="favorite mt-3 {{$answer->status}}"
                                    onclick="event.preventDefault();
                                    document.getElementById('accept-answer-{{$answer->id}}').submit()">
                                        <i class="fas fa-check fa-2x"></i>
                                    </a>
                                        <form id="accept-answer-{{$answer->id}}" style="display: none" action="{{route('accept.answers',$answer->id)}}" method="post">
                                            @csrf
                                        </form>
                                        @else
                                        @if($answer->is_best)
                                            <a class="favorite mt-3 {{$answer->status}}">
                                                <i class="fas fa-check fa-2x"></i>
                                            </a>
                                        @endif
                                    @endcan
                                </div>
                                <answer :answer="{{$answer}}" inline-template>
                                <div class="media-body">
                                    <form v-if="editing" @submit.prevent="update">
                                        <div class="form-group">
                                            <textarea required class="form-control" v-model="body" rows="10"></textarea>
                                        </div>
                                        Edit The Form
                                        <button :disabled="isInvalid" class="btn btn-outline-primary">Update</button>
                                        <button class="btn btn-outline-secondary" @click="cancel">Cancel</button>
                                    </form>
                                    <div v-else>
                                        <div :answer="bodyHtml"></div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="ml-auto">
                                                @can('update', $answer)
                                                    <a @click.prevent="editing=true" class="btn btn-sm btn-outline-primary">Edit</a>
                                                @endcan
                                                @can('delete', $answer)
                                                  <button @click="destroy" class="btn btn-outline-danger">
                                                      delete
                                                  </button>
                                                @endcan
                                            </div>
                                        </div>
                                        <div class="col-md-4"></div>
                                        <div class="col-md-4">
                                            <div class="float-right">
                                                <!----------vuejs------->
                                                <user-info :model="{{$answer}}" label="asked"></user-info>
                                            </div>
                                        </div>
                                    </div>
                                    </div>
                                </div>
                                </answer>
                            </div>
                            <hr>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
@endsection
