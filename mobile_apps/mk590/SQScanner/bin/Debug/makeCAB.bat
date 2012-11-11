@echo off
if exist err.log del err.log
if exist showcode.cab del showcode.cab
CabWiz.exe ShowCode.inf /err err.log 
if exist err.log type err.log
pause