function my_levenshtein(str1, str2)
{
    if (str1.length != str2.length){
        return -1;
    }
    var count = 0;
    for (let i = 0; i < str1.length; i++)
    {
        if(str1[i] != str2[i])
        {
            count += 1;
        }
    }
    return count;
}