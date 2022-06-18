import numpy as np
import math

sudoku = "1200000130400100"
class Sudoku:
    def __init__(self, sudoku):
        self.problem =  sudoku
        self.answer = None
        self.edge = self.get_edge(self.problem) #辺を取得
    
    def get_edge(self, sudoku):
        return int(math.sqrt(len(sudoku)))

    def show_sudoku(self, sudoku):
        if sudoku == None:
            return 
        print(np.array(list(sudoku)).reshape(self.edge, self.edge))
        print('---------------------------------------')

    #def solve_allSearch(self):

    def exe(self):          #実行
        self.show_sudoku(self.problem)
        #self.solve_allSearch(self.problem)
        self.show_sudoku(self.answer)

test1 = Sudoku(sudoku)
test1.exe()



