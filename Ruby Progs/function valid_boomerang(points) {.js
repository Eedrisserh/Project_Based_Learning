function valid_boomerang(points) {
    var [x1, y1] = points[0];
    var [x2, y2] = points[1];
    var [x3, y3] = points[2];
    return (x1 * (y2 - y3) + x2 * (y3 - y1) + x3 * (y1 - y2)) != 0;
    // (1 * (3 - 2) + 2 * (2 - 1) + 3 * (1 - 3)) ! = 0;
    //      1 + 2 -6 = -3   
  }

  console.log(valid_boomerang([[1,1],[2,3],[3,2]])) //returns true

  console.log(valid_boomerang( [[1,1],[2,2],[3,3]])) //returns false

  // x1 = 1, x2 = 2, y1 = 1, y2 = 3, x3 = 3, y3 = 2