#include <stdio.h>
#include <stdarg.h>
#include <string.h>
#include <unistd.h>
#include <limits.h>
#include <stdlib.h>
#include <stdint.h>

/**
 * @brief Function to print a single character to stdout.
 * 
 * @param c Character to print to stdout.
 * @return ** char printed character.
 */
int _putchar(char c){
    (write(1, &c, 1));
    return 1;
}

/**
 * @brief Function to print a string to stdout.
 * 
 * @param string Set of strings to print
 * @return ** int number of characters printed
 */
int print_string(va_list args){
    int count = 0;
    char *s = va_arg(args, char*);

    if (s == NULL){
        count += _putchar('(');
        count += _putchar('n');
        count += _putchar('u');
        count += _putchar('l');
        count += _putchar('l');
        count += _putchar(')');
    return count;
  }

    while(*s != '\0'){
        count += _putchar(*s);
        s++;
    }
    return count;
}

/**
 * @brief Print_int is a function that prints the given integer value to the console
 * 
 * @param num is the number to print
 * @return int returns the number of integers printed
 */
int print_int(int num) {
    int count = 0;

    if (num < 0) {
        _putchar('-');
        num = -num; //this removes the negative sign
        count++;
    }

    if (num >= 10) {
        count += print_int(num / 10);
    }

    _putchar(num % 10 + '0');
    count++;
    return count;
}

/**
 * @brief print_octal prints the octal representation of a number
 * 
 * @param num unsigned int number to print the octal representation of
 * @return ** number of octal characters printed
 */
int print_octal(unsigned int num){
  int count = 0;

  if (num > 1)
    count += print_octal(num / 8);

  _putchar(num % 8 + '0');
  count ++;
  return count;
}

/**
 * @brief print_uint prints an unsigned integer representation of an integer
 * 
 * @param num integer number to convert
 * @return ** return the number of unsigned bytes written
 */
int print_uint(unsigned num) {
    int count = 0;

    if ((int)num < 0) {
        num -= UINT_MAX;
    }

    if (num >= 10) {
        count += print_int(num / 10);
    }

    _putchar(num % 10 + '0');
    count++;
    return count;
}

/**
 * @brief print_hex prints a hexadecimal representation of an integer value in upper case.
 * 
 * @param num integer value to convert to hexacimal representation
 * @return ** returns the number of hexadecimal digits printed
 */
int print_hex(unsigned int num){
  int count = 0;
  if ((int)num == 0)
    return count;
  
  count += print_hex(num / 16);
  if (num % 16 < 10){
    _putchar(num % 16 + '0');
    count++;
  }
  else{
    _putchar(num % 16 - 10 + 'A');
    count++;
  }
  return count;
}

/**
 * @brief print_pointer_hex prints the address of a pointer in a hexadecimal in lower case
 * 
 * @param num int address to be converted to a hexadecimal
 * @return ** number of hex digits printed
 */
int print_pointer_hex(unsigned long int num) {
  int count = 0;
  char hex_digit;

  if (num > 10) {
      count += print_pointer_hex(num / 16);
  }
  if (num % 16 < 10) {
      hex_digit = '0' + (num % 16);
  } else {
      hex_digit = 'a' + (num % 16) - 10;
  }
  _putchar(hex_digit);
  count++;
  return count;
}

/**
 * @brief print_pointer extracts the pointer address and prints the first 0x
 * 
 * @param args va_list type containing the address
 * @return ** numbers of characters printed
 */
int print_pointer(va_list args){
    int count = 0;
    void *ptr = va_arg(args, void*);
    long temp = (uintptr_t)ptr;

    count += _putchar('0');
    count += _putchar('x');
    count += print_pointer_hex(temp);

    return count;
}

int my_printf(char *restrict format, ...) {
  int i;
  int num_args = 0;

  va_list args; // Create a va_list variable

  va_start(args, format); // Initialize the va_list variable to the beginning of the variable arguments

  for (i = 0; format[i] != '\0'; i++) // Iterate through the variable arguments, printing them out
  {
    if (format[i] == '%'){
        int temp;
        
      switch (format[i + 1])
      {
        case 'c':
          _putchar(va_arg(args, int));// the int here converts the char to it equivalent ascii value & pass it to _putchar 
          num_args++;
          i += 1;
          break;

        case 'd':
          num_args += print_int(va_arg(args, int));
          i += 1;
          break;

        case 'o':
          num_args += print_octal(va_arg(args, unsigned int));
          i += 1;
          break;

        case 's':
          num_args += print_string(args); // passed only args because print_string accepts va_list type
          i += 1;
          break;

        case 'u':
          num_args += print_uint(va_arg(args, unsigned int));
          i += 1;
          break;
          
        case 'x':
          temp = va_arg(args, int);
          if (temp == 0) {
            num_args += _putchar('0'); // handle the first 0 outside print_hex so it won't print twice
          }
          else {
            num_args += print_hex((unsigned int)temp);
          }
          i += 1;
          break;

        case 'p':
          num_args += print_pointer(args);
          i += 1;
          break;
          
        default:
          _putchar(format[i]);
          num_args ++;
          break;
      }      
    }
   else{
    _putchar(format[i]);
    num_args ++;
    }
  }
  va_end(args);   // Clean up the va_list variable
  return num_args;
}

int main() {
   unsigned int num = 1;
    char t = 'H';      
    // int count1 = printf("\n%p", &num);
    // printf("\nCount is:%d\n", count1);

    // int count = my_printf("\n %c\n%p",'I', &num);
    // my_printf("\nmy_printf Count is:%d\n", count);

    my_printf("%c", t);

 
    return 0;
}