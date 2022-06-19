import numpy as np
import math

test1 = "1200000130400100"
class Sudoku:
    def __init__(self, sudoku):
        self.edge = self.get_edge(sudoku) #辺を取得
        self.sudoku =  np.array(list(sudoku)).reshape(self.edge, self.edge)
    
    def get_edge(self, sudoku):
        return int(math.sqrt(len(sudoku)))

    def show_sudoku(self, sudoku):
        print(sudoku)
        print('---------------------------------------')

    def solve_allSearch(self, sudoku):
        mask = [sudoku == '0']
        self.check(sudoku)
    
    def check(self, sudoku):
        for j in range(self.edge): #横方向
            sudoku[0][j] 
            
        

    def exe(self):          #実行
        self.show_sudoku(self.sudoku)
        answer = self.solve_allSearch(self.sudoku)
        self.show_sudoku(answer)

t1 = Sudoku(test1)
t1.exe()



