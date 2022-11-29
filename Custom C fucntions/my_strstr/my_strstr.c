#include <stdio.h>
char* my_strstr(char* param_1, char* param_2)
{
    char *str = param_1;
    char *substring = param_2;
  
  while(1)
  {
    if ( !*substring )
		return param_1;
		
    if ( !*param_1 )
		return NULL;
		
    if ( *str++ != *substring++)
	{
		str = ++param_1;
		substring = param_2;
	}
  }
}