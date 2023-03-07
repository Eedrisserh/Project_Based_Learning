#include <stdio.h>
#include <string.h>
#include <stdlib.h>

int main (int argc, char **argv)
{
   FILE *file;
   char buf[1024];

   for (int i = 1; i < argc; i++)
   {
    file = fopen (argv[i], "r");
    
    while (fgets(buf, sizeof(buf), file) != NULL)
        printf("%s\n", buf);
   }
   fclose(file);
    return (0);
}