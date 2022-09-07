sudoku = "800000000003600000070090200050007000000045700000100030001000068008500010090000400" #https://rocketnews24.com/2012/07/03/22654/
#!/usr/bin/python3 --
# -*- coding: utf-8 -*-

from cProfile import run
from glob import glob
import sys

#sudoku = sys.stdin.readline()

class NP:
    def __init__(self, sudoku):
        self.sudoku = sudoku
        self.kai = ""
        self.cnt = 0

    def is_solve(self,s): #解答可能か
        if s.count("0") > (81 - 17): #数字が17以上入力
            return False

        yoko = [s[k*9:k*9+9] for k in range(9)] #縦
        tate = [s[k::9] for k in range(9)] #横
        block = [] #ブロック
        for k in range(9):
            StrA = "".join([s[k//3*27+k%3*3+l//3*9+l%3] for l in range(9)])
            block.append(StrA)

        if self.check(tate) == -1:
            return False
        if self.check(yoko) == -1:
            return False
        if self.check(block) == -1:
            return False
    
        return True

    def check(self,li): #重複チェック
        for a in li:
            for num in range(1, 10):
                if a.count(str(num)) > 1:
                    return -1

    def fi(self,s): #次にどこのマスに数字を入れればいいか(入る候補が最も少ないのはどこか)を判定します
        _ = []
        used_yoko = [set([s[k%9+l*9] for l in range(9)]) for k in range(9)] #あらかじめ、各列各行各ブロックで使用済みの数字をリストアップしておきます
        used_tate = [set([s[k*9+l] for l in range(9)]) for k in range(9)]
        used_block = [set([s[k//3*27+k%3*3+l//3*9+l%3] for l in range(9)]) for k in range(9)]
        used_yoko.remove("0") if ("0" in used_yoko) else None
        used_tate.remove("0") if ("0" in used_tate) else None
        used_block.remove("0") if ("0" in used_block) else None
        for k in range(81):
            if s[k] != "0":
                _ += [0]
            else:
                ss = used_yoko[k%9]|used_tate[k//9]|used_block[k//27*3+k%9//3]
                _ += [len(set(ss))]
        k_next = _.index(max(_))
        ss_next =used_yoko[k_next%9]|used_tate[k_next//9]|used_block[k_next//27*3+k_next%9//3]
        return k_next,set(map(str,range(1,10)))-set(ss_next)

    def dfs(self,i,t): #全探索を行う部分です
        if i == self.sudoku.count("0"):        
            self.cnt += 1
            self.kai = t 
            if self.cnt > 1:
                return False
        else:
            k,next_number = self.fi(t)
            for j in next_number:
                _ = self.dfs(i+1,t[:k]+str(j)+t[k+1:])
                if _ is False:
                    return _

    def run_solve(self):
        if self.is_solve(self.sudoku):
            self.dfs(0,str(self.sudoku))
            if self.cnt == 1: #解が唯一か
                print(self.kai)
            else:
                return None
        else:
            return None

np = NP(sudoku)
np.run_solve()