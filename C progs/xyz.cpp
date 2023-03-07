#include <stdbool.h>
#include <stdio.h>

void my_is_sort(int *param_1, int size)
{
    int i, count = 0;
//    if (size <= 1)
//    	printf("True");


    if (param_1[0] > param_1[1]) //for Descending order 9, 8, 7 ....
    {
        for (i = 0; i < size; i++)
        {
        	printf("This is I: %d\n", i);
            if (param_1[i] < param_1[i + 1])
            {
            	printf("True");
				count += 1;
            }
        }
    }
}

int main ()
{
	int arr[] = {2, 1, -1};
	int len = sizeof(arr) / sizeof(arr[0]);
	

	my_is_sort(arr, len);
	
	return 0;
}