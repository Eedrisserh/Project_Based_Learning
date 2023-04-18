const DrawBoard = () =>{ // Draw the main game board
    for ( r = 0; r < row; r++ ){
        for ( c = 0; c < col; c++ ){
            DrawSquare(c, r, board[r][c]);
        }
    }
}

const DrawNextBoard = () =>{ // Draw next tetromino board
  for ( r = 0; r < Row; r++ ){
      for ( c = 0; c < Col; c++ ){
          DrawNextSquare(c, r, Nextboard[r][c]);
      }
  }
}

class Piece {
    constructor(tetromino, color) {
      this.tetromino = tetromino;
      this.color = color;
      this.tetroNum = 0;
      this.activeTetro = this.tetromino[this.tetroNum];
      this.x = 3;
      this.y = -2;
    }


    fill(color){ // changes the board color
      for (let r = 0; r < this.activeTetro.length; r++) {
        for (let c = 0; c < this.activeTetro.length; c++) {
          if (this.activeTetro[r][c]) {
            DrawSquare(this.x + c, this.y + r, color);
          }
        }
      }
    }

    draw() {
      this.fill(this.color);
    }

    undraw() {
      this.fill(vacant);
  }

  movedown() {
    if (!this.collisionCheck(0, 1, this.activeTetro)){
      this.undraw();
      this.y++;
      this.draw();
    }else{
      p = nextPiece;
      this.lockPiece();
      nextPiece =  pieceGen();
      displayNextShape(nextPiece);
    }
  }

  moveright() {
    if (!this.collisionCheck(1, 0, this.activeTetro)){
      this.undraw();
      this.x++;
      this.draw();
    }
  }

  moveleft() {
    if (!this.collisionCheck(-1, 0, this.activeTetro)){
      this.undraw();
      this.x--;
      this.draw();
    }
  }

  rotate() {
    let nextpattern = this.tetromino[(this.tetroNum + 1) % this.tetromino.length];
    let kick = 0;
    
    if(this.collisionCheck(0, 0, nextpattern)){
      if (this.x > col / 2){
        kick = - 1;
      }else{
        kick = 1;
      }
    }
    
    if (!this.collisionCheck(0, 0, nextpattern)){
      this.undraw();
      this.x += kick
      this.tetroNum = (this.tetroNum + 1) % this.tetromino.length;
      this.activeTetro = this.tetromino[this.tetroNum];
      this.draw();
    }
  }

  hardDrop() {
    while(!this.collisionCheck(0, 1, this.activeTetro)){
      this.movedown();
    }
  }

  collisionCheck(x, y, piece) { // returns true if there will be a collision after the movement otherwise false
    for( r = 0; r < piece.length; r++ ) {
      for( c = 0; c < piece[r].length; c++ ) {
        if(!piece[r][c]){
          continue;
        }
        let newx = this.x + c + x;
        let newy = this.y + r + y;

        if(newy >= row || newx < 0 || newx >= col ) {
          return true;
        }
        if(newy < 0){
          continue;
        }
        if (board[newy][newx] != vacant){
          return true;
        }
      }
    }
    return false;
  }

  lockPiece() { // locks the piece on the board
    for (r = 0; r < this.activeTetro.length; r++) {
      for (c = 0; c < this.activeTetro.length; c++) {
        if (!this.activeTetro[r][c]) {
          continue;
        }
        if (this.y + r < 0) {
          alert("Game Over!");
          gameover = true;
          break;
        }
        board[this.y + r][this.x + c] = this.color;
      }
    }
    
     for (r = 0; r < row; r++) {
      let fullrow = true;
      for (c = 0; c < col; c++) {
        fullrow = fullrow && (board[r][c] != vacant); // returns true if all rows are filled else false
      }
      if (fullrow) {
        for ( let y = r; y > 1; y-- ) { // added let to y
          for ( c = 0; c < col; c++) {
            board[y][c] = board[y - 1][c];
          }
        }
        for ( c = 0; c < col; c++) {
          board[0][c] = vacant;
        }
        score += 10;
     }
    }
    DrawBoard();
    scores.innerHTML = score;
  }
}
