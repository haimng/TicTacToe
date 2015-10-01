#[GOAL] 
To build a tic-tac-toe program that allows the user to play against the computer, with the computer always forcing the user to win.

#[Solution] 
Using Minimax algorithm that calculates all possible moves from the current move, 
for each move find next possible moves until the end of game, and then assign score for each move based on the game's result. 
Finally, picking the moves with scores so that the computer always lose.

Reference: https://en.wikipedia.org/wiki/Minimax

#How to run ?
> php TicTacToe.php
