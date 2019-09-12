<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];
    protected $appends= ['url', 'avatar'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    //One to many relationship
    public function questions(){
        return $this->hasMany(Question::class);
    }
    public function getUrlAttribute(){
//        return route('questions.show', $this->slug);
        return '#';
    }

    public function answers(){
        return $this->hasMany(Answer::class);
    }
    public function getAvatarAttribute(){
        $email = $this->email;
        $size = 30;
        return "https://www.gravatar.com/avatar/" . md5( strtolower( trim( $email ) ) ) . "&s=" . $size;
    }
    public function favorites(){
        return $this->belongsToMany(Question::class, 'favorites')
            ->withTimestamps();
    }
    public function voteQuestions(){
        return $this->morphedByMany(Question::class, 'votable')->withTimestamps();
    }
    public function voteAnswers(){
        return $this->morphedByMany(Answer::class, 'votable')->withTimestamps();
    }
    public function voteQuestion(Question $question, $vote){
        $voteQuestions = $this->voteQuestions();
        if ($voteQuestions->where('votable_id', $question->id)->exists()){
            $voteQuestions->updateExistingPivot($question,['vote'=>$vote]);
        }else{
            $voteQuestions->attach($question, ['vote'=> $vote]);
        }

        $question->load('votes');
        $upVotes = $question->upVotes()->sum('vote',1);
        $downVote = $question->downVotes()->sum('vote',-1);

        $question->votes_count = $upVotes + $downVote;
        $question->save();
    }
    public function voteAnswer(Answer $answer, $vote){
        $voteAnswers = $this->voteAnswers();
        if ($voteAnswers->where('votable_id', $answer->id)->exists()){
            $voteAnswers->updateExistingPivot($answer,['vote'=>$vote]);
        }else{
            $voteAnswers->attach($answer, ['vote'=> $vote]);
        }

        $answer->load('votes');
        $upVotes = $answer->upVotes()->sum('vote',1);
        $downVote = $answer->downVotes()->sum('vote',-1);

        $answer->votes_count = $upVotes + $downVote;
        $answer->save();
    }
}
