<?php
// [GOAL] 
// To build a tic-tac-toe program that allows the user to play against the computer, with the computer always forcing the user to win.

// [Solution] 
// Using Minimax algorithm that calculates all possible moves from the current move, 
// for each move find next possible moves until the end of game, and then assign score for each move based on the game's result. 
// Finally, picking the moves with scores so that the computer always lose.
// Reference: https://en.wikipedia.org/wiki/Minimax

// How to run ?
// > php TicTacToe.php 

class TicTacToe {
    
    const HUMAN = 1;
    const COMPUTER = 2;
    
    private $deck;
    private $player;
    
    public function TicTacToe(){
        echo "\n\nCongrats! You will always will ;-) \n";
        
        // Init deck
        $this->deck = array(
            array(0,0,0),
            array(0,0,0),
            array(0,0,0),
        );
        $this->printDeck();
        
        // Human goes first
        $this->player = self::HUMAN; 
    }
    
    public function run(){
        echo "Input your move (format: ROW,COLUMN). <example>: 0,0 or 0,1 or 1,2\n\n";

        while(!$this->isFinished($this->deck)){
            $myMove = $this->readMove();
        
            $this->deck = $this->nextDeck($this->deck, $myMove, $this->player);
            $this->printDeck();
            if($this->isFinished($this->deck))    break;
        
            // Turn to computer
            $this->player = $this->nextPlayer($this->player);
        
            // Computer find a move
            $move = array(0,0);
            $depth = 0;
            $score = $this->minimax($this->deck, $depth, $this->player, $move);
            echo "Computer move [$this->player]: ".$move[0].",".$move[1].". Score: $score\n";
        
            $this->deck = $this->nextDeck($this->deck, $move, $this->player);
            $this->printDeck();
        
            // Turn to human
            $this->player = $this->nextPlayer($this->player);
        }
        
        $winner = $this->winner($this->deck);
        if($winner == self::HUMAN){
            echo "You are the Winner! Congrats :) \n\n\n";
        }else{
            echo "Unfortunately, the winner is Computer :( \n\n\n";
        }
    }
    
    private function readMove(){
        do {
            $isValid = true;
            
            $move = readline("Your move: ");
            $move = explode(",",$move);
            
            if(count($move) != 2)     $isValid = false;
            
            foreach($move as $k => $m){
                $m = trim($m);
                $move[$k] = $m; 
                if($m !== '0' && $m !== '1' && $m !== '2'){
                    $isValid = false;
                }
            }
            
            if($isValid && $this->deck[$move[0]][$move[1]] != 0)    $isValid = false;
            
            if(!$isValid)    echo "Your move is wrong format, value or position (format: ROW,COLUMN). <Example> 0,0 or 0,1 or 1,2\n";
        }while(!$isValid);
            
        return $move;
    }
    
    public function minimax($deck, $depth, $player, &$move){
        if($this->isFinished($deck)){
            return $this->score($deck, $depth);
        }
    
        $depth += 1;
        $scores = array();
        $moves = array();
    
        $nextMoves = $this->nextMoves($deck);
        $nextPlayer = $this->nextPlayer($player);
        foreach($nextMoves as $nextMove){
            $nextDeck = $this->nextDeck($deck, $nextMove, $player);
            $scores[] = $this->minimax($nextDeck, $depth, $nextPlayer, $move);
            $moves[] = $nextMove;
        }
    
        if($player == self::COMPUTER){
            $maxScoreIndexs = array_keys($scores, max($scores));
            $maxScoreIndex = $maxScoreIndexs[0];
    
            $move = $moves[$maxScoreIndex];
            return $scores[$maxScoreIndex];
        }else{
            $minScoreIndexs = array_keys($scores, min($scores));
            $minScoreIndex = $minScoreIndexs[0];
    
            $move = $moves[$minScoreIndex];
            return $scores[$minScoreIndex];
        }
    }
    
    public function score($deck, $depth){
        $winner = $this->winner($deck);
        if($winner == self::HUMAN){
            return 10 - $depth;
        }else if($winner == self::COMPUTER){
            return $depth - 10;
        }else{
            return 0;
        }
    }
    
    public function nextPlayer($player){
        return ($player == self::HUMAN) ? self::COMPUTER : self::HUMAN;
    }
    
    public function nextMoves($deck){
        $nextMoves = array();
        foreach($deck as $r => $row){
            foreach($row as $c => $p){
                if($p == 0){
                    $nextMoves[] = array($r,$c);
                }
            }
        }
        return $nextMoves;
    }
    
    public function nextDeck($deck, $nextMove, $player){
        $deck[$nextMove[0]][$nextMove[1]] = $player;
        return $deck;
    }
    
    public function isFinished($deck){
        if($this->winner($deck) != 0){
            return true;
        }
    
        foreach($deck as $r => $row){
            foreach($row as $c => $p){
                if($p == 0){
                    return false;
                }
            }
        }
        return true;
    }
    
    public function winner($deck){
        $colDeck = $deck;
        
        // Check each row
        foreach($deck as $r => $row){
            $player = $row[0];
            if( $player > 0 && 
                $row[0] == $row[1] && $row[1] == $row[2]) {
                return $player;
            }
            
            // Fill in the column-deck
            $colDeck[0][$r] = $row[0];
            $colDeck[1][$r] = $row[1];
            $colDeck[2][$r] = $row[2];
        }
        
        // Check each column
        foreach($colDeck as $col){
            $player = $col[0];
            if( $player > 0 &&
                $col[0] == $col[1] && $col[1] == $col[2]) {
                return $player;
            }
        }
        
        // Check 2 diagonal lines
        $player = $deck[1][1];
        if(   $player > 0 &&
           (  $deck[0][0] == $deck[1][1] && $deck[1][1] == $deck[2][2]
           || $deck[0][2] == $deck[1][1] && $deck[1][1] == $deck[2][0] )
        ) {
            return $player;
        }
        
        return 0;
    }
    
    public function printDeck(){
        foreach($this->deck as $r => $row){
            foreach($row as $c => $p){
                echo $p." ";
            }
            echo "\n";
        }
        echo "\n";
    }
    
    public function debug($obj){
        echo print_r($obj,true);
    }
}

$ticTacToe = new TicTacToe();
$ticTacToe->run();

?>