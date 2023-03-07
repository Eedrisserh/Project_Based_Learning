#include <stdio.h>
#include <stdlib.h>
#include <string.h>

// Function to count the number of occurrences of each character in the input
void count_characters(char *input)
{
    int input_length = strlen(input);

    // An array to store the count of each character
    int count[128] = { 0 };

    // Loop through the input string and count the occurrences of each character
    for (int i = 0; i < input_length; i++)
        count[(int)input[i]]++;

    // Loop through the count array and print the count of each character
    for (int i = 0; i < 128; i++)
    {
        if (count[i] > 0)
            printf("%c:%d\n", i, count[i]);
    }
}

// Driver code
int main()
{
    char input[] = "abcdef";

    count_characters(input);

    return 0;
}
