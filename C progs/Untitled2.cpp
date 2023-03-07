#include <stdio.h>
int main (int argc, char *argv[])
{
    int i;

    printf("The Value received by argc is %d.\n", argc);
    printf("There are %d command line arguments passed to main().\n", argc);
    if (argc !=0)
    {
        printf("The first command-line argument which is also the name of the program is: %s\n", argv[0]);
        printf("The rest of the command line arguments are: \n");

        while (i < argc)
        {
            printf("%s\n", argv[i]);
            i++;
        }
        return 0;
    }
}
