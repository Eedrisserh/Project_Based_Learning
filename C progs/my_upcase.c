#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <unistd.h>

int putint(int x)
{
    int count = 0;
    int original_x = x;
    if (x == 0) {
        count++;
    } else {
        while (x != 0) {
            x /= 10;
            count++;
        }
    }
    write(1, &original_x, sizeof(original_x));
    return count;
}



int main()
{
	int x = putint(2345);
	printf("\nPutint returns :%d\n", x);
	return 0;
}
