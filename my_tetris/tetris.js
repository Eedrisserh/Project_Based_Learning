const cvs = document.getElementById("Tetris");
const ctx = cvs.getContext("2d");
const scores = document.getElementById("score");

const row = 20;
const col = column = 10;
const sq = squareSize = 20;
const vacant = "white";

const DrawSquare = (x, y, color) => {
  ctx.fillStyle = color;
  ctx.fillRect(x * sq, y * sq, sq, sq);
  ctx.strokeStyle = vacant;
  ctx.strokeRect(x * sq, y * sq, sq, sq);
};

const Cvs = document.getElementById("NextTetro");
const Ctx = Cvs.getContext("2d");

const Row = 4;
const Col = column = 4;

const DrawNextSquare = (x, y, color) => {
  Ctx.fillStyle = color;
  Ctx.fillRect(x * sq, y * sq, sq, sq);
  Ctx.strokeStyle =vacant;
  Ctx.strokeRect(x * sq, y * sq, sq, sq);
};

let board = new Array(row).fill().map(() => new Array(col).fill(vacant));
let Nextboard = new Array(Row).fill().map(() => new Array(Col).fill(vacant));

DrawBoard();

DrawNextBoard();

const pieces = [  
    [I, "cyan"],
    [O, "yellow"],
    [T, "purple"],
    [S, "green"],
    [Z, "red"],
    [J, "blue"],
    [L, "orange"],
];

const pieceGen = () =>{
  let r = Math.floor(Math.random() * pieces.length);
  return new Piece(pieces[r][0], pieces[r][1]);
}

function displayNextShape(nextPiece) {
  // Clear the next tetrimino board
  for(let r = 0; r < Row; r++){
      for(let c = 0; c < Col; c++){
          Nextboard[r][c] = vacant;
      }
  }
  // Draw the next tetrimino shape on the board
  for(let r = 0; r < nextPiece.tetromino[nextPiece.tetroNum].length; r++){
      for(let c = 0; c < nextPiece.tetromino[nextPiece.tetroNum].length; c++){
          if(nextPiece.tetromino[nextPiece.tetroNum][r][c]){
            Nextboard[r][c] = nextPiece.color;
          }
      }
  }
  DrawNextBoard();
}

let nextPiece = pieceGen();
displayNextShape(nextPiece);

let p = pieceGen();   // Variables declaration section
let score = 0;
let drop = Date.now();
let gameover = false;
let interval = 1000;

const autoDrop = () => {
    let now = Date.now();
    let diff = now - drop;
  if (diff > interval){
    p.movedown();
    drop = Date.now();
  }
  if(!gameover)
    requestAnimationFrame(autoDrop);
}

  const control = (event) => {
    if(event.keyCode == 37){
        p.moveleft();
        drop = Date.now();
    }else if(event.keyCode == 38){
        p.rotate();
        drop = Date.now();
    }else if(event.keyCode == 39){
        p.moveright();
        drop = Date.now();
    }else if(event.keyCode == 40){
        p.movedown();
        drop = Date.now();
    }else if (event.keyCode == 32){
        p.hardDrop();
        drop = Date.now();
    }
}
  document.addEventListener("keydown", control);
  autoDrop()
