#include <stdio.h>
/**
 * @brief a robber that cannot rob adjacent houses and has to calculate which house 
 * combination will give them the highest money
 * 
 * @param nums 
 * @param numsSize 
 * @return ** int 
 */

int rob(int* nums, int numsSize){

    int i;
    int odd = 0, even = 0;
    for (i=0; i<numsSize; i++){
        if (i % 2 == 0)
            even += nums[i];
        else
            odd += nums[i];
    }
    if (odd > even) return odd;
        else return even;
}

int main(){
 int nums [] = {2,7,9,3,1};

 printf("%d\n", rob(nums, sizeof(nums) / sizeof(nums[0])));
 
    return 0;
}
 
