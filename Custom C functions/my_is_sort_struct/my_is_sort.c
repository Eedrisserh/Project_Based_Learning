#include <stdbool.h>
#ifndef STRUCT_INTEGER_ARRAY
#define STRUCT_INTEGER_ARRAY

typedef struct s_integer_array
{
    int size;
    int* array;
} integer_array;
#endif


bool my_is_sort(integer_array* param_1)
{
    int i, j;

    for (i = 0; i < param_1 -> size - 1; i++) // Looping through the array
        {
            if (param_1 ->array[i] <= param_1 ->array[i + 1])
            {} //check if it's ascending and do nothing

            else //if descending start a loop
            {
                for(j = 0; j < param_1->size - 1; j++)
                {
                    if (param_1->array[j] >= param_1->array[j + 1]) //check if array stills descends and do nothing
                    {}
                    else //return false if it does not continue to descend
                        return false;
                }
                if (j == param_1->size - 1)
                    return true;
            }
        }
     if (i == param_1->size - 1) // if size == x in the first loop
        return true;

    return true;
    }