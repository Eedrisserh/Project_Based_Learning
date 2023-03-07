#ifndef MAIN_H
#define MAIN_h
#include <stdio.h>
#include <ctype.h>
#include <unistd.h>

/**
 * _putchar - writes the character c to stdout
 * @c: The character to print
 *
 * Return: On success 1.
 * On error, -1 is returned, and errno is set appropriately.
 */
 
int _putchar(char c)
{
	return (write(1, &c, 1));
}
/**
 * Print_most_numbers - function that prints single numbers
 * return: set of single numbers
 */
void print_most_numbers(void)
{
for (int i = 0; i <= 9; i++)
		{
			if(i == 2 || i == 4)
			continue;
			{
			_putchar(i + '0');	
			_putchar(44);
			_putchar(32);
			}
		}
		_putchar(10);
}
/**
 * Largest_number - Function  to find the largest number
 * @a: is an integer
 * @b: is another integer
 * @c: is another integer
 * @largest: Returns the largest number between the integers
 * Return: On success returns the largest number
 */

int largest_number(int a, int b, int c)
{
    int largest;

    if ((a > b ) && (a > c))
    {
        largest = a;
    }
    else if ((b > a) && (b > c))
    {
        largest = b;
    }
	 else if ((c > a) && (c > b))
    {
        largest = c;
    }

    return (largest);
}

/**
 * _isupper - Function that checks for upper case
 * @c: Is the variable that accepts the characters
 * Return: on success return 0
 */
 int _isupper(int c)
{
	if (isupper(c))
		return (1);
	else
		return (0);

}

/**
 * mul - Function that multiplies two integers
 * @a: Is the variable that holds the first integer
 * @b: Is the variable that holds the second integer
 * Return: on success return 0
 */

int mul(int a, int b)
{
	int result = a * b;
	printf("Sum of %d and %d is: %lu", a, b, result);
	return (result);
}










/**
 * print_number - Function
 * @a: is an integer
 * @b: is another integer
 * @c: is another integer
 * @largest: Returns the largest number between the integers
 * Return: On success returns the largest number
 */
void print_number(int n)
{
	unsigned int i = n;

	if (n < 0)
	{
		putchar(45);
		i = -i;
	}
	if (i / 10)
	{
		print_number(i / 10);
	}
	putchar(i % 10 + '0');
}
#endif