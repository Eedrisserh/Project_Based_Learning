#include <stdio.h>

/**
 * main - Writes 00 - 99
 * @void: Empty parameter list for main.
 *
 * Description: Writes all unique combinations
 * of 2 numbers
 *
 * Return: 0 for success
*/
/* function declaration */
//int max(int num1, int num2, int no);

/* function declaration */
#include <stdio.h>

/* function declaration */
void print_big(int number);

int main() 
{
	int n = 5, i, j;
	for ( i = 0; i <= n; i++ )
	{
		for ( j = 0; j <= i; j++)
		{
		printf("#");
		
		}
	
}
  return 0;
}

void print_big(int number){
    if(number > 10){
        printf("%d is big\n",number);
    }
}