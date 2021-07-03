#!c:/Python3.9/python.exe
# dibujarGrafica.py

from lifelines import KaplanMeierFitter
import mysql.connector
import pandas as pd
import numpy as np
from datetime import date
import sys
import os

def main():
    separacion = sys.argv[1]
    opciones = sys.argv[2]
    opcionesArr = opciones.split('-')
    map_object = map(int, opcionesArr)

    opcionesArr = list(map_object)
    miConexion, cur = establecerConexionBase()
    pacientes = crearDataFrame(cur, separacion, opcionesArr)
    if(separacion != "general"):
        opcionesSeparacion = obtenerOpciones(cur, separacion)
        dibujarGrafica(pacientes, separacion, opcionesSeparacion)
    else:
        dibujarGrafica(pacientes, separacion, None)
    
def establecerConexionBase():
    miConexion = mysql.connector.connect( host='localhost', user= 'root', passwd='', db='prueba' )
    
    return miConexion, miConexion.cursor()    

def crearDataFrame(cur, separacion, opcionesArr):
    if(separacion == "general"):
        cur.execute('SELECT pacientes.id_paciente, fecha_diagnostico, s.fecha FROM pacientes INNER JOIN enfermedades on pacientes.id_paciente = enfermedades.id_paciente LEFT JOIN (SELECT id_paciente, fecha, estado FROM seguimientos where estado = \'Fallecido\') s on pacientes.id_paciente = s.id_paciente')
    else:
        cur.execute('SELECT pacientes.id_paciente, '+separacion+', fecha_diagnostico, s.fecha FROM pacientes INNER JOIN enfermedades on pacientes.id_paciente = enfermedades.id_paciente LEFT JOIN (SELECT id_paciente, fecha, estado FROM seguimientos where estado = \'Fallecido\') s on pacientes.id_paciente = s.id_paciente')
    table_rows = cur.fetchall()
    
    pacientes = pd.DataFrame(table_rows)

    if(separacion == "general"):
        pacientes.rename(columns = {0 : 'id_paciente', 1 : 'Fecha diagnostico', 2 : 'Fecha fallecimiento'}, inplace = True)
    else:
        pacientes.rename(columns = {0 : 'id_paciente', 1 : separacion, 2 : 'Fecha diagnostico', 3 : 'Fecha fallecimiento'}, inplace = True)
    
    fallecidos = np.ones([pacientes.shape[0],1], int)
    for i in range(pacientes.shape[0]):
        if pacientes['Fecha fallecimiento'][i] == None:
            fallecidos[i] = 0
    today = date.today()
    
    pacientes.loc[pacientes['Fecha fallecimiento'].isnull() , 'Fecha fallecimiento'] = today
    pacientes['Fallecidos'] = fallecidos

    pacientes = pacientes[pacientes['id_paciente'].isin(opcionesArr)]


    pacientes['Tiempo vivos'] = (pacientes['Fecha fallecimiento'] - pacientes['Fecha diagnostico']).dt.days / 365
    pd.to_numeric(pacientes['Tiempo vivos'])
    
    return pacientes

def obtenerOpciones(cur, separacion):
    cur.execute('SELECT DISTINCT '+separacion+' FROM pacientes')
    opciones = np.array(cur.fetchall())
    
    return opciones

def dibujarGrafica(pacientes, separacion, opcionesSeparacion):
    kmf = KaplanMeierFitter()

    T = pacientes["Tiempo vivos"]
    E = pacientes["Fallecidos"]
    if(separacion != "general"):
        listaBooleanos = []
        for opcion in opcionesSeparacion:
            listaBooleanos.append(pacientes[separacion] == opcion[0])
        for i in range(len(listaBooleanos)):
            kmf.fit(T[listaBooleanos[i]], E[listaBooleanos[i]], label=opcionesSeparacion[i][0])
            ax = kmf.plot_survival_function()
    
        fig = ax.get_figure()
        fig.savefig("C:/wamp64/www/TFG_HUBU/Laravel/public/kaplanmeierDivisiones.png")
    else:
        kmf.fit(T, E, label="Pacientes")
        ax = kmf.plot_survival_function()
        
        fig = ax.get_figure()
        fig.savefig("C:/wamp64/www/TFG_HUBU/Laravel/public/kaplanmeierGeneral.png")
     
if __name__ == "__main__":
    main()