#include <stdio.h>
#include <string.h>
#include <stdlib.h>
/**
  * main - Entry point
  *
  * Return: Always 0 (Success)
  */
int main(void)
{
    char fn[] = "Idriss";
    char sn[] = "Ibrahim";
    char *str_con;
    int fn_l = strlen(fn);
    int sn_l = strlen(sn);
    int i, j = 0, lenght = fn_l + sn_l;


    str_con = (char*)malloc (sizeof(char) * lenght);
    if (str_con == NULL)
            return (NULL);
    for (i = 0; i <= fn_l; i++)
            str_con[i] = fn[i];
    for (; i <= lenght; i++)
    {
    str_con[i] = sn[j];
    j++;
    }
   for (i = 0; i <= lenght; i++)
    printf("%c", str_con[i]);
    return (0);
}
