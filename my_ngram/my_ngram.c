#include <stdio.h>
#include <string.h>
#include <stdlib.h>

int main (int argc, char **argv)
{

int input[128] = {0};
int arr_length = sizeof(input) / sizeof(input[0]);

for(int i = 1; i < argc; i++ )
{
    for (int k = 0; k < (int)strlen(argv[i]); k++)
        input[(int)argv[i][k]] += 1;
}

for (int j = 0; j < arr_length; j++)
{
    if (input[j] > 0)
        printf("%c:%d\n",j, input[j]);
}
    return 0;
}