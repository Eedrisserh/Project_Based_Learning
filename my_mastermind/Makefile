SRC=my_mastermind.c
TARGET=my_mastermind
OBJ=my_mastermind.o
CFLAGS=-Wall -Werror -Wextra

all: $(TARGET)

$(TARGET):$(OBJ)
	gcc $(CFLAGS) -o $(TARGET) $(OBJ)	

$(OBJ):$(SRC)
	gcc $(CFLAGS) -c $(SRC)

clean:
	rm -f *.o

fclean:clean
	rm -f $(TARGET)

re: fclean all