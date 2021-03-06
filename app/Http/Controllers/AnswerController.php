<?php

namespace App\Http\Controllers;

use App\Answer;
use App\Question;
use Illuminate\Http\Request;

class AnswerController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Question $question,Request $request)
    {
        $question->answers()->create($request->validate([
                'body' => 'required'
            ])+ ['user_id' => \Auth::id()]);
        return back()->with('success','Answer Submitted');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Answer  $answer
     * @return \Illuminate\Http\Response
     */
    public function edit(Question $question, Answer $answer)
    {
        $this->authorize('update',$answer);
        return view('answers.edit',compact('question','answer'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Answer  $answer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,Question $question, Answer $answer)
    {
        $this->authorize('update',$answer);
        $answer->update($request->validate([
            'body' => 'required'
        ]));
        if ($request->expectsJson()){
            return response()-> json([
                'massage' => 'Answer update successfully',
                'body_html' => $answer->body_html
            ]);
        }
        return redirect()->route('questions.show',$question->slug)
            ->with('success','Answer Updated Succesfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Answer  $answer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Question $question,Answer $answer)
    {
        $this->authorize('delete',$answer);
        $answer->delete();
        if (request()->expectsJson()){
            return response()->json([
                'Message'=> 'Answer Delete successfully'
            ]);
        }
        return back()->with('success','Answer is Deleted');
    }
}
