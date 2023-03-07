#include <stdlib.h>
#include <time.h>
#include <stdio.h>
#include <unistd.h>
#include "main.h" 

/**
 * Main - A program that prints the numbers from 1 to 100
 * followed by a new line. But for multiples of three print Fizz
 * instead of the number and for the multiples of five print Buzz.
 * For numbers which are multiples of both three and five
 * print FizzBuzz.
 * Return : Numbers 1 -100 with fizz, buzz and fizzbuzz
 */
 
int main(void)
{ 
	int num = 1;
	
	while (num <= 100)
	{
		if (num % 3 != 0 && num % 5 != 0)
				printf("%d ", num);
		else if (num % 3 == 0 && num % 5 == 0)		
			printf("FizzBuzz ");
		else if (num % 3 == 0)
			printf("Fizz ");
		else if (num % 5 == 0)
			printf("Buzz ");
	
	num ++; 
	}
	putchar(10);
    return (0);
}