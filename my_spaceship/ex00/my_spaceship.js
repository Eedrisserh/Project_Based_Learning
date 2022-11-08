function my_spaceship(flight)
{
    var direction = 'up';
    var y_axis = 0;
    var x_axis = 0;
    for (let command of flight)
    {
        if (direction =='up')
        {
            switch (command)
            {
                case 'R':
                    direction = 'right';
                    break;
                case 'L':
                    direction = 'left'
                    break;
                case 'A':
                    y_axis --;
                default:
                    break;
            }
        }
        else if (direction == 'right')
        {
            switch (command)
            {
                case 'R':
                    direction = 'down';
                    break;
                case 'L':
                    direction = 'up';
                    break;
                case 'A':
                    x_axis ++;
                    break;
                default:
                    break;
            }
        }
        else if (direction == 'down')
        {
            switch (command)
            {
                case 'R':
                    direction = 'left';
                    break;
                case 'L':
                    direction = 'right';
                    break;
                case 'A':
                    y_axis ++;
                default:
                    break;
            }
        }
        else if (direction == 'left')
        {
            switch(command)
            {
                case 'R':
                    direction = 'up';
                    break;
                case 'L':
                    direction = 'down'
                    break;
                case 'A':
                    x_axis --;
                default:
                    break;
            }
        }
    }
   return "{x: "+x_axis+", y: "+y_axis+", direction: "+"'"+direction+"'}";
}
// console.log (my_spaceship('RAARA'))