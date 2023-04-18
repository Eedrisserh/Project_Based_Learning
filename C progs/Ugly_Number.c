/*
**
** QWASAR.IO -- ugly_number
**
** @param {int} param_1
**
** @return {bool}
**
*/
#include <stdbool.h>
#include <stdio.h>
bool ugly_number(int param_1)
{
  
  while (param_1 >= 2){
    if (param_1 % 2 == 0){
      param_1 /= 2;
      printf("Param_1 /2: %d\n", param_1);
    }
    else if (param_1 % 3 == 0){
      param_1 /= 3;
      printf("Param_1 /3: %d\n", param_1);
    }
    else if (param_1 % 5 == 0){
      param_1 /= 5;
      printf("Param_1 /5: %d\n", param_1);
    }
    else
    	break;
  }
  printf("Param_1: %d\n", param_1);
  if (param_1 > 1){
    return false;
  }
  else
    return true;
}

int main (){
	ugly_number(14);
	return 0;
}
