TARGET=my_ngram
OBJ=my_ngram.o
SRC=my_ngram.c
CFLAGS=-Wall -Wextra -Werror

all: $(TARGET)

$(OBJ): $(SRC)
	gcc $(CFLAGS) -c $(SRC)

$(TARGET): $(OBJ)
	gcc $(CFLAGS) -o $(TARGET) $(OBJ)

clean:
	rm -f *.o

fclean: clean
	rm -f $(TARGET)

re:fclean all