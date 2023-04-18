#ifndef STRUCT_INTEGER_ARRAY
#define STRUCT_INTEGER_ARRAY
typedef struct s_integer_array
{
    int size;
    int* array;
} integer_array;
#endif

int find_pivot(integer_array* param_1, int param_2)
{
  int i = 0;
  int sum = 0;
  int left = 0;
  while (i < param_1-> size){
    sum += param_1 -> array[i];
    i++;
    }
  
  for (int j = 0; j < param_2; j++){
    sum -= param_1 -> array[j];
    if (left == sum ){
    return j;
    }else{
      left += param_1 -> array[j];
    }
  }
  return -1;
}

int main(){
	
	integer_array* input = {1, 2, 3, 4, 0, 6};
	int len = 6;
	find_pivot(input, len);
	
	return 0;
}