#!c:/Python3.9/python.exe
# prueba.py

import sys
import random
from datetime import date
from datetime import datetime
import numpy as np
#Librería para poder establecer la conexión a mysql
import mysql.connector
#Librería para crear los nombres, apellidos y direcciones aleatorios
from faker import Faker

#Constantes de los tipos de datos

#Datos paciente
sexo = ["Masculino", "Femenino"]
raza = ["Caucásico", "Asiático", "Africano", "Latino"]
profesion = ["Construcción", "Minería", "Pintor", "Peluquero", "Industria textil", "Mecánico", "Limpieza", "Cerámicas", "Desconocido"]
fumador = ["Fumador", "Exfumador", "Nunca fumador", "Desconocido"]
bebedor = ["Bebedor", "Exbebedor", "Nunca bebedor", "Desconocido"]
carcinogenos = ["Asbesto", "Desconocido"]

#Datos enfermedad
afectacion = ["Uni ganglionar", "Multiestación"]	
M = ["0", "1a", "1b", "1c"]
numAfectacion = ["0", "1", "2-4", "Mayor que 4"]
TNM = ["IA1", "IA2", "IA3", "IB", "IIA", "IIB", "IIIA", "IIIB", "IIIC", "IVa", "IVb"]
tipoMuestra = ["Citología", "Biopsia", "Bloque celular desde citología"]
tipoHistologia = ["Adenocarcinoma", "Epidermoide", "Adenoescamoso", "Carcinoma de células grandes", "Sarcomatoide", "Indiferenciado"]
subtipoHistologia = ["Desconocido", "Acinar", "Lepidico", "Papilar", "Micropapilar", "Solido", "Mucinoso", "Celulas claras"]
gradoHistologia = ["Bien diferenciado", "Moderamente diferenciado", "Mal diferenciado", "No especificado"]

#Datos sintomas
tipoSintoma = ["Asintomático", "Tos", "Pérdida de peso", "Anorexia", "Aumento de expectoración", "Hemoptisis", "Dolor torácico", "Dolor otra localización", "Clínica neurológica", "Fractura patológica", "Desconocido"]

#Datos metastasis
tipoMetastasis = ["Pulmón contralatera", "Implantes pleurales", "Derrame pleural", "Hígado", "Hueso", "Suprarrenal", "Renal", "SNC", "Derrame pericárdio", "Carcinomatosis meníngea", "Linfangitis pulmonar carcinomatosa", "Adenopatías supradiafragmáticas extratorácicas", "Adenopatías infradiafragmáticas", "Páncreas", "Peritoneo", "Cutánea", "Muscular", "Tejidos blandos"]

#Datos biomarcadores
nombreBiomarcador = ["NGS", "PDL1", "EGFR", "ALK", "ROS1", "KRAS", "BRAF", "HER2", "NTRK", "FGFR1", "RET", "MET", "Pl3K", "TMB"]
tipoNGS = ["Biopsia sólida", "Biopsia líquida"]
subtipoNGS = ["Oncomine Focus", "Oncodeep", "Guardant 360", "Focus DX", "FoliguidLDX"]
tipoPDL1 = ["En célula tumorales con IHQ", "Score combinado/CPS"]
subtipoPDL1 = ["≤ 1%", "1-49%", "≥ 50%"]
tipoEGFR = ["Exón 19", "Exón 21", "Exón 20", "Exón 18", "T790M positivo"]
tipoALK = ["Traslocado", "No traslocado", "Mutación"]
subtipoALK = ["Gusión EML4"]
tipoROS1 = ["Traslocado", "No traslocado"]
tipoKRAS = ["Mutado", "No mutado"]
subtipoKRAS = ["KRASG12C", "KRASG12V"]
tipoBRAF = ["Mutado", "No mutado", "Clase I", "Clase II", "Clase III"]
subtipoBRAF = ["BRAFV600E"]
tipoHER2 = ["Mutado", "Amplificado", "No alterado"]
tipoNTRK = ["Mutado", "No mutado", "Reordenado"]
subtipoNTRK = ["NTRK 1", "NTRK 2", "NTRK 3"]
tipoFGFR1 = ["Amplificado", "No amplificado", "Mutación"]
tipoRET = ["Traslocado", "No traslocado", "Mutación"]
tipoMET = ["Mutación Exón 14", "No mutado", "Amplificado", "Sobreexpresado"]
tipoPl3K = ["Mutado", "No mutado"]
tipoTMB = ["Alto", "Bajo"]

#Datos pruebas realizadas
tipoPrueba = ["Radiografía de tórax", "TAC", "TAP", "TAC SNC", "PETTAC", "GGO", "RMN", "SNC", "RMN body"]

#Datos tecnicas realizadas
tipoTecnica = ["Broncoscopia", "EBUS", "Mediastinoscopia", "BAG pulmonar", "BAG extrapulmonar", "Cirugía diagnóstico-terapéutica"]

#Datos otros tumores
tipoOtroTumor = ["Pulmón", "ORL", "Vejiga", "Renal", "Colon", "Mama", "Páncreas", "Esofagogástrico", "Próstata", "Ginecológico", "Hígado", "Linfático", "SNC"]

#Datos antecedentes medicos
tipoAntecedenteMedico = ["HTA", "DM", "DL", "EPOC", "Asma", "IAM", "Ictus", "Enfermedad autoinmune", "VIH", "Tuberculosis"]

#Datos antecedentes oncologicos
tipoAntecedenteOncologico = ["Pulmón", "ORL", "Vejiga", "Renal", "Páncreas", "Esofagogástrico", "Próstata", "Hígado", "Ginecológico", "Linfático", "SNC"]

#Datos antecedentes familiares
tipoAntecedenteFamiliar = ["Padre", "Madre", "Abuelo", "Abuela"]
tipoEnfermedadFamiliar = ["Pulmón", "ORL", "Vejiga", "Renal", "Páncreas", "Esofagogástrico", "Próstata", "Hígado", "Ginecológico", "Linfático", "SNC"]

#Datos trataientos
tipoTratamiento = ["Quimioterapia", "Radioterapia", "Cirugía"]
subtipoQuimioterapia = ["Neoadyuvancia", "Adyuvancia", "Enfermedad avanzada"]
tipoEnsayoClinico = ["Observacional", "Experimental"]
esquema = ["Monoterapia", "Combinación"]
modoAdministracion = ["Oral", "Intravenoso"]
tipoFarmaco = ["Quimioterapia", "Inmunoterapia", "Tratamiento dirigido", "Antiangiogénico", "Quimoterapia + Inmunoterapia", "Tratamiento dirigido", "Quimioterapia + Tratamiento dirigido", "Quimioterapia + Antiangiogénico"]
farmacos = ["Cisplatino", "Carboplatino", "Vinorelbina", "Paclitaxel", "Nab-paclitaxel" ,"Docetaxel", "Pemetrexed", "Gemcitabina", "Bevacizumab", "Ramucirumab", "Nintedanib", "Nivolumab", "Pembrolizumab", "Atezolizumab", "Avelumab", "Erlotinib", "Gefinitib", "Afatinib", "Dacomitinib", "Osimertinib", "Mobocertinib", "Amivantamab", "Crizotinib", "Alectinib", "Brigatinib", "Ceritinib", "Lorlatinib", "Dabratinib", "Trametinib", "Tepotinib", "Capmatinib", "Trastuzumab-deruxtecán"]
subtipoRadioterapia = ["Radical", "Paliativa"]
localizacionRadioterapia = ["Pulmonar", "Pulmonar + mediastino", "Ósea", "Suprarrenal", "SNC", "Hígado", "Ganglionar"]	
subtipoCirugia = ["Neumonectomía", "Lobectomía", "Bilobectomía", "Segmentectomía", "Resección", "Resección atípica"]

#Datos reevaluaciones
estadoReevaluacion = ["Sin evidencia de enfermedad/respuesta completa", "Respuesta parcial", "Enfermedad estable", "Progresión", "Recaída"]
localizacionProgresion = ["Pulmón contralateral", "Implantes pleurales", "Derrame pleural", "Hígado", "Hueso", "Suprarrenal", "Renal", "SNC", "Derrame pericárdico", "Carcinomatosis meníngea", "Linfangitis pulmonar carcinomatosa", "Adenopatías supradiafragmáticas extratorácicas", "Adenopatías infragmáticas", "Páncreas", "Peritoneo", "Cutánea", "Muscular", "Tejidos blandos"]
tipoTratamientoProgresion = ["Tratamiento activo", "Cuidados paliativos"]

#Datos seguimientos
estadoSeguimiento = ["Vivo sin enfermedad", "Vivo con enfermedad", "Fallecido"]
motivoFallecimiento = ["Enfermedad", "Otro"]

#Inicializamos la variable con un objeto tipo Faker pasando por argumento el idioma Español
fake = Faker('es_ES')

#Variables media y moda para las distribuciones normales
mediaTumor = float(sys.argv[2])
desviacionTumor = float(sys.argv[3])
mediaDosis = float(sys.argv[4])
desviacionDosis = float(sys.argv[5])
lambdaCigarros = float(sys.argv[6])
lambdaCiclos = float(sys.argv[7])

def main():
    miConexion, cur = establecerConexionBase()
    numPacientes = int(sys.argv[1])
    for x in range(1, numPacientes+1):
        insertarPaciente(x, cur, miConexion)
        insertarEnfermedad(x, x, cur, miConexion)
        numTablas = obtenerNumeroTablas()
        for i in range(numTablas[0]):
            insertarSintoma(x, cur, miConexion)
        for i in range(numTablas[1]):
            insertarMetastasis(x, cur, miConexion)
        for i in range(numTablas[2]):
            insertarBiomarcador(x, cur, miConexion)
        for i in range(numTablas[3]):
            insertarPruebaRealizada(x, cur, miConexion)
        for i in range(numTablas[4]):
            insertarTecnicaRealizada(x, cur, miConexion)
        for i in range(numTablas[5]):
            insertarOtroTumor(x, cur, miConexion)
        for i in range(numTablas[6]):
            insertarAntecedenteMedico(x, cur, miConexion)
        for i in range(numTablas[7]):
            insertarAntecedenteOncologico(x, cur, miConexion)
        for i in range(1, numTablas[8]):
            idAntecedente = obtenerIdMaximo(cur, "Antecedentes_familiares") + 1
            insertarAntecedenteFamiliar(idAntecedente, x, cur, miConexion)
            for j in range(numTablas[9]):
                insertarEnfermedadFamiliar(idAntecedente, cur, miConexion)
        for i in range(1, numTablas[10]):
            idTratamiento = obtenerIdMaximo(cur, "Tratamientos") + 1
            insertarTratamiento(idTratamiento, x, cur, numTablas[11], miConexion)
        for i in range(numTablas[12]):
            insertarReevaluacion(x, cur, miConexion)
        for i in range(numTablas[13]):
            insertarSeguimiento(x, cur, miConexion)
        miConexion.commit()


    miConexion.close()

def obtenerNumeroTablas():
    numeroInserts = []
    p = float(sys.argv[8].replace(',', '.'))
    for x in range(14):
        numeroInserts.append(np.random.geometric(p, 1)[0])


    return numeroInserts

def obtenerIdMaximo(cur, tabla):
    if(tabla == "Antecedentes_familiares"):
        cur.execute("SELECT max(id_antecedente_f) from antecedentes_familiares")
    elif(tabla == "Tratamientos"):   
        cur.execute("SELECT max(id_tratamiento) from tratamientos")
    myresult = cur.fetchone()
    if(myresult[0] == None):
        return 0
    return myresult[0]

def establecerConexionBase():
    miConexion = mysql.connector.connect( host='localhost', user= 'root', passwd='', db='pruebasintetica' )
    return miConexion, miConexion.cursor()	

def insertarPaciente(id_paciente, cur, miConexion):
    id_paciente = str(id_paciente)
    paciente = Paciente()
    if(paciente.fumador == "Fumador" or paciente.fumador == "Exfumador"):
        cur.execute( "INSERT INTO pacientes (id_paciente, nombre, apellidos, sexo, raza, nacimiento, profesion, fumador, num_tabaco_dia, bebedor, ultima_modificacion, carcinogenos) VALUES ('"+id_paciente+"','"+paciente.nombre+"','"+paciente.apellidos+"','"+paciente.sexo+"','"+paciente.raza+"','"+paciente.nacimiento+"','"+paciente.profesion+"','"+paciente.fumador+"','"+paciente.numeroCigarros+"','"+paciente.bebedor+"','"+paciente.ultimaModificacion+"','"+paciente.carcinogenos+"');")
    else:
        cur.execute( "INSERT INTO pacientes (id_paciente, nombre, apellidos, sexo, raza, nacimiento, profesion, fumador, bebedor, ultima_modificacion, carcinogenos) VALUES ('"+id_paciente+"','"+paciente.nombre+"','"+paciente.apellidos+"','"+paciente.sexo+"','"+paciente.raza+"','"+paciente.nacimiento+"','"+paciente.profesion+"','"+paciente.fumador+"','"+paciente.bebedor+"','"+paciente.ultimaModificacion+"','"+paciente.carcinogenos+"');")
    miConexion.commit()

def insertarEnfermedad(idEnfermedad, idPaciente, cur, miConexion):
    idEnfermedad = str(idEnfermedad)
    idPaciente = str(idPaciente)
    enfermedad = Enfermedad()
    cur.execute("INSERT INTO enfermedades (id_enfermedad ,id_paciente, fecha_primera_consulta, fecha_diagnostico, ECOG, T, T_tamano, N, N_afectacion, M, num_afec_metas, TNM, tipo_muestra, histologia_tipo, histologia_subtipo, histologia_grado, tratamiento_dirigido) VALUES ('"+idEnfermedad+"','"+idPaciente+"','"+enfermedad.fecha_primera_consulta+"','"+enfermedad.fecha_diagnostico+"','"+enfermedad.ECOG+"','"+enfermedad.T+"','"+enfermedad.T_tamano+"','"+enfermedad.N+"','"+enfermedad.N_afectacion+"','"+enfermedad.M+"','"+enfermedad.numAfectacion+"','"+enfermedad.TNM+"','"+enfermedad.tipoMuestra+"','"+enfermedad.tipoHistologia+"','"+enfermedad.subtipoHistologia+"','"+enfermedad.gradoHistologia+"','"+enfermedad.tratamientoDirigido+"');")
    miConexion.commit()

def insertarSintoma(idEnfermedad, cur, miConexion):
    idEnfermedad = str(idEnfermedad)
    sintoma = Sintoma()
    cur.execute("INSERT INTO sintomas (id_enfermedad, tipo, fecha_inicio) VALUES ('"+idEnfermedad+"','"+sintoma.tipoSintoma+"','"+sintoma.fecha_inicio+"');")
    miConexion.commit()

def insertarMetastasis(idEnfermedad, cur, miConexion):
    idEnfermedad = str(idEnfermedad)
    metastasis = Metastasis()
    cur.execute("INSERT INTO metastasis (id_enfermedad, tipo) VALUES ('"+idEnfermedad+"','"+metastasis.tipoMetastasis+"');")
    miConexion.commit()

def insertarBiomarcador(idEnfermedad, cur, miConexion):
    idEnfermedad = str(idEnfermedad)
    biomarcador = Biomarcador()
    if(biomarcador.subtipo == "Null"):
        cur.execute("INSERT INTO biomarcadores (id_enfermedad, nombre, tipo) VALUES ('"+idEnfermedad+"','"+biomarcador.nombreBiomarcador+"','"+biomarcador.tipo+"');")
    else:
        cur.execute("INSERT INTO biomarcadores (id_enfermedad, nombre, tipo, subtipo) VALUES ('"+idEnfermedad+"','"+biomarcador.nombreBiomarcador+"','"+biomarcador.tipo+"','"+biomarcador.subtipo+"');")
    miConexion.commit()

def insertarPruebaRealizada(idEnfermedad, cur, miConexion):
    idEnfermedad = str(idEnfermedad)
    pruebaRealizada = PruebaRealizada()
    cur.execute("INSERT INTO pruebas_realizadas (id_enfermedad, tipo) VALUES ('"+idEnfermedad+"','"+pruebaRealizada.tipoPrueba+"');")
    miConexion.commit()

def insertarTecnicaRealizada(idEnfermedad, cur, miConexion):
    idEnfermedad = str(idEnfermedad)
    tecnicaRealizada = TecnicaRealizada()
    cur.execute("INSERT INTO tecnicas_realizadas (id_enfermedad, tipo) VALUES ('"+idEnfermedad+"','"+tecnicaRealizada.tipoTecnica+"');")
    miConexion.commit()

def insertarOtroTumor(idEnfermedad, cur, miConexion):
    idEnfermedad = str(idEnfermedad)
    otroTumor = OtroTumor()
    cur.execute("INSERT INTO otros_tumores (id_enfermedad, tipo) VALUES ('"+idEnfermedad+"','"+otroTumor.tipoOtroTumor+"');")
    miConexion.commit()

def insertarAntecedenteMedico(idPaciente, cur, miConexion):
    idPaciente = str(idPaciente)
    antecedenteMedico = AntecedenteMedico()
    cur.execute("INSERT INTO antecedentes_medicos (id_paciente, tipo_antecedente) VALUES ('"+idPaciente+"','"+antecedenteMedico.tipoAntecedenteMedico+"');")
    miConexion.commit()

def insertarAntecedenteOncologico(idPaciente, cur, miConexion):
    idPaciente = str(idPaciente)
    antecedenteOncologico = AntecedenteOncologico()
    cur.execute("INSERT INTO antecedentes_oncologicos (id_paciente, tipo) VALUES ('"+idPaciente+"','"+antecedenteOncologico.tipoAntecedenteOncologico+"');")
    miConexion.commit()

def insertarAntecedenteFamiliar(idAntecedente, idPaciente, cur, miConexion):
    idAntecedente = str(idAntecedente)
    idPaciente = str(idPaciente)
    antecedenteFamiliar = AntecedenteFamiliar()
    cur.execute("INSERT INTO antecedentes_familiares (id_antecedente_f, id_paciente, familiar) VALUES ('"+idAntecedente+"','"+idPaciente+"','"+antecedenteFamiliar.tipoAntecedenteFamiliar+"');")
    miConexion.commit()

def insertarEnfermedadFamiliar(idAntecedenteFamiliar, cur, miConexion):
    idAntecedenteFamiliar = str(idAntecedenteFamiliar)
    enfermedadFamiliar = EnfermedadFamiliar()
    cur.execute("INSERT INTO enfermedades_familiar (id_antecedente_f, tipo) VALUES ('"+idAntecedenteFamiliar+"','"+enfermedadFamiliar.tipoEnfermedadFamiliar+"');")
    miConexion.commit()

def insertarTratamiento(idTratamiento, idPaciente, cur, numFarmacos, miConexion):
    idTratamiento = str(idTratamiento)
    idPaciente = str(idPaciente)
    tratamiento = Tratamiento()
    if(tratamiento.tipoTratamiento == "Quimioterapia"):
        cur.execute("INSERT INTO tratamientos (id_tratamiento, id_paciente, tipo, subtipo, dosis, localizacion, fecha_inicio, fecha_fin) VALUES ('"+idTratamiento+"','"+idPaciente+"','Quimioterapia','"+tratamiento.subtipoQuimioterapia+"', Null, Null,'"+tratamiento.fechaInicio+"','"+tratamiento.fechaFin+"');")
        miConexion.commit()
        if(tratamiento.tipoEnsayoClinico == "Null"):
            cur.execute("INSERT INTO intenciones (id_intencion, id_tratamiento, tratamiento_acceso_expandido, tratamiento_fuera_indicacion, medicacion_extranjera, esquema, modo_administracion, tipo_farmaco, numero_ciclos) VALUES ('"+idTratamiento+"','"+idTratamiento+"','"+tratamiento.tratamientoAcceso+"','"+tratamiento.tratamientoFuera+"','"+tratamiento.medicacionExtranjera+"','"+tratamiento.esquema+"','"+tratamiento.modoAdministracion+"','"+tratamiento.tipoFarmaco+"','"+tratamiento.numeroCiclos+"')")
        else:
            cur.execute("INSERT INTO intenciones (id_intencion, id_tratamiento, ensayo, ensayo_fase, tratamiento_acceso_expandido, tratamiento_fuera_indicacion, medicacion_extranjera, esquema, modo_administracion, tipo_farmaco, numero_ciclos) VALUES ('"+idTratamiento+"','"+idTratamiento+"','"+tratamiento.tipoEnsayoClinico+"','"+tratamiento.ensayoClinicoFase+"','"+tratamiento.tratamientoAcceso+"','"+tratamiento.tratamientoFuera+"','"+tratamiento.medicacionExtranjera+"','"+tratamiento.esquema+"','"+tratamiento.modoAdministracion+"','"+tratamiento.tipoFarmaco+"','"+tratamiento.numeroCiclos+"')")
        miConexion.commit()
        for i in range(numFarmacos):
            insertarFarmaco(idTratamiento, cur, miConexion)
    elif(tratamiento.tipoTratamiento == "Radioterapia"):
        cur.execute("INSERT INTO tratamientos (id_paciente, tipo, subtipo, dosis, localizacion, fecha_inicio, fecha_fin) VALUES ('"+idPaciente+"','Radioterapia','"+tratamiento.subtipoRadioterapia+"','"+tratamiento.dosis+"','"+tratamiento.localizacionRadioterapia+"','"+tratamiento.fechaInicio+"','"+tratamiento.fechaFin+"');")
        miConexion.commit()
    else:
        cur.execute("INSERT INTO tratamientos (id_paciente, tipo, subtipo, dosis, localizacion, fecha_inicio, fecha_fin) VALUES ('"+idPaciente+"','Cirugia','"+tratamiento.subtipoCirugia+"', Null, Null,'"+tratamiento.fechaInicio+"', Null);")
        miConexion.commit()

def insertarFarmaco(idIntencion, cur, miConexion):
    idIntencion = str(idIntencion)
    farmaco = Farmaco()
    cur.execute("INSERT INTO farmacos (id_intencion, tipo) VALUES ('"+idIntencion+"','"+farmaco.nombreFarmaco+"');")
    miConexion.commit()

def insertarReevaluacion(idPaciente, cur, miConexion):
    idPaciente = str(idPaciente)
    reevaluacion = Reevaluacion()
    if(reevaluacion.localizacionProgresion == "Null"):
        cur.execute("INSERT INTO reevaluaciones (id_paciente, fecha, estado) VALUES ('"+idPaciente+"','"+reevaluacion.fechaReevaluacion+"','"+reevaluacion.estadoReevaluacion+"');")
    else:
        cur.execute("INSERT INTO reevaluaciones (id_paciente, fecha, estado, progresion_localizacion, tipo_tratamiento) VALUES ('"+idPaciente+"','"+reevaluacion.fechaReevaluacion+"','"+reevaluacion.estadoReevaluacion+"','"+reevaluacion.localizacionProgresion+"','"+reevaluacion.tipoTratamientoProgresion+"');")
    miConexion.commit()

def insertarSeguimiento(idPaciente, cur, miConexion):
    idPaciente = str(idPaciente)
    seguimiento = Seguimiento()
    if(seguimiento.fechaFallecimiento == "Null"):
        cur.execute("INSERT INTO seguimientos (id_paciente, fecha, estado) VALUES ('"+idPaciente+"','"+seguimiento.fechaSeguimiento+"','"+seguimiento.estadoSeguimiento+"');")
    else:
        cur.execute("INSERT INTO seguimientos (id_paciente, fecha, estado, fallecido_motivo, fecha_fallecimiento) VALUES ('"+idPaciente+"','"+seguimiento.fechaSeguimiento+"','"+seguimiento.estadoSeguimiento+"','"+seguimiento.motivoFallecimiento+"','"+seguimiento.fechaFallecimiento+"');")
    miConexion.commit()

class Paciente:

    def __init__(self):
        self.nombre = fake.first_name()
        self.apellidos = fake.last_name()
        self.sexo = sexo[random.randint(0,1)]
        self.raza = raza[random.randint(0,3)]
        self.nacimiento = fake.date()
        self.profesion = profesion[random.randint(0,8)]
        self.fumador = fumador[random.randint(0,3)]
        if(self.fumador == "Fumador" or self.fumador == "Exfumador"):
            self.numeroCigarros = str(np.random.poisson(lambdaCigarros, 1)[0])
        self.bebedor = bebedor[random.randint(0,3)]
        self.carcinogenos = carcinogenos[random.randint(0,1)]
        self.ultimaModificacion = str(date.today())

class Enfermedad:

    def __init__(self):
        self.fecha_primera_consulta = fake.date()
        fecha_inicio = datetime.strptime(self.fecha_primera_consulta+' 00:00:00', '%Y-%m-%d %H:%M:%S')
        self.fecha_diagnostico = str(fake.date_time_between_dates(datetime_start=fecha_inicio).date())
        self.ECOG = str(random.randint(0,4))
        self.T = str(random.randint(1,4))
        self.T_tamano = str(np.around(np.random.normal(mediaTumor, desviacionTumor, 1), 2)[0])
        self.N = str(random.randint(1,3))
        self.N_afectacion = afectacion[random.randint(0,1)]
        self.M = M[random.randint(0,3)]
        self.numAfectacion =  numAfectacion[random.randint(0,3)]
        self.TNM = TNM[random.randint(0,10)]
        self.tipoMuestra = tipoMuestra[random.randint(0,2)]
        self.tipoHistologia = tipoHistologia[random.randint(0,5)]
        self.subtipoHistologia = subtipoHistologia[random.randint(0,7)]
        self.gradoHistologia = gradoHistologia[random.randint(0,3)]
        self.tratamientoDirigido = str(random.randint(0,1))

class Sintoma:

    def __init__(self):
        self.tipoSintoma = tipoSintoma[random.randint(0,10)]
        self.fecha_inicio = fake.date()

class Metastasis:

    def __init__(self):
        self.tipoMetastasis = tipoMetastasis[random.randint(0,17)]

class Biomarcador:

    def __init__(self):
        self.nombreBiomarcador = nombreBiomarcador[random.randint(0,13)]
        if(self.nombreBiomarcador == "NGS"):
            self.tipo = tipoNGS[random.randint(0,1)]
            self.subtipo = subtipoNGS[random.randint(0,4)]
        elif(self.nombreBiomarcador == "PDL1"):
            self.tipo = tipoPDL1[random.randint(0,1)]
            self.subtipo = subtipoPDL1[random.randint(0,2)]
        elif(self.nombreBiomarcador == "EGFR"):
            self.tipo = tipoEGFR[random.randint(0,4)]
            self.subtipo = "Null"
        elif(self.nombreBiomarcador == "ALK"):
            self.tipo = tipoALK[random.randint(0,2)]
            if(self.tipo == "Traslocado"):
                self.subtipo = subtipoALK[0]
            else: 
                self.subtipo = "Null"
        elif(self.nombreBiomarcador == "ROS1"):
            self.tipo = tipoROS1[random.randint(0,1)]
            self.subtipo = "Null"
        elif(self.nombreBiomarcador == "KRAS"):
            self.tipo = tipoKRAS[random.randint(0,1)]
            if(self.tipo == "Mutado"):
                self.subtipo = subtipoKRAS[random.randint(0,1)]
            else:
                self.subtipo = "Null"
        elif(self.nombreBiomarcador == "BRAF"):
            self.tipo = tipoBRAF[random.randint(0,4)]
            if(self.tipo == "Mutado"):
                self.subtipo = subtipoBRAF[0]
            else:
                self.subtipo = "Null"
        elif(self.nombreBiomarcador == "HER2"):
            self.tipo = tipoHER2[random.randint(0,2)]
            self.subtipo = "Null"
        elif(self.nombreBiomarcador == "NTRK"):
            self.tipo = tipoNTRK[random.randint(0,2)]
            self.subtipo = subtipoNTRK[random.randint(0,2)]
        elif(self.nombreBiomarcador == "FGFR1"):
            self.tipo = tipoFGFR1[random.randint(0,2)]
            self.subtipo = "Null"
        elif(self.nombreBiomarcador == "RET"):
            self.tipo = tipoRET[random.randint(0,2)]
            self.subtipo = "Null"
        elif(self.nombreBiomarcador == "MET"):
            self.tipo = tipoMET[random.randint(0,3)]
            self.subtipo = "Null"
        elif(self.nombreBiomarcador == "Pl3K"):
            self.tipo = tipoPl3K[random.randint(0,1)]
            self.subtipo = "Null"
        elif(self.nombreBiomarcador == "TMB"):
            self.tipo = tipoTMB[random.randint(0,1)]
            self.subtipo = "Null"

class PruebaRealizada:

    def __init__(self):
        self.tipoPrueba = tipoPrueba[random.randint(0,8)] 

class TecnicaRealizada:

    def __init__(self):
        self.tipoTecnica = tipoTecnica[random.randint(0,5)] 

class OtroTumor:

    def __init__(self):
        self.tipoOtroTumor = tipoOtroTumor[random.randint(0,12)] 

class AntecedenteMedico:

    def __init__(self):
        self.tipoAntecedenteMedico = tipoAntecedenteMedico[random.randint(0,9)] 

class AntecedenteOncologico:

    def __init__(self):
        self.tipoAntecedenteOncologico = tipoAntecedenteOncologico[random.randint(0,10)] 

class AntecedenteFamiliar:

    def __init__(self):
        self.tipoAntecedenteFamiliar = tipoAntecedenteFamiliar[random.randint(0,3)] 

class EnfermedadFamiliar:

    def __init__(self):
        self.tipoEnfermedadFamiliar = tipoEnfermedadFamiliar[random.randint(0,10)]

class Tratamiento:

    def __init__(self):
        self.tipoTratamiento = tipoTratamiento[random.randint(0,2)]
        if(self.tipoTratamiento == "Quimioterapia"):
            self.subtipoQuimioterapia = subtipoQuimioterapia[random.randint(0,2)]
            self.ensayoClinico = random.randint(0,1)
            if(self.ensayoClinico == 1):
                self.tipoEnsayoClinico = tipoEnsayoClinico[random.randint(0,1)]
                self.ensayoClinicoFase = str(random.randint(1,4))
            else:
                self.tipoEnsayoClinico = "Null"
                self.ensayoClinicoFase = "Null"
            self.tratamientoAcceso = str(random.randint(0,1))
            self.tratamientoFuera = str(random.randint(0,1))
            self.medicacionExtranjera = str(random.randint(0,1))
            self.esquema = esquema[random.randint(0,1)]
            self.modoAdministracion = modoAdministracion[random.randint(0,1)]
            self.tipoFarmaco = tipoFarmaco[random.randint(0,7)]
            self.numeroCiclos = str(np.random.poisson(lambdaCiclos, 1)[0])
            self.fechaInicio = fake.date()
            self.fechaFin = fake.date()
        elif(self.tipoTratamiento == "Radioterapia"):
            self.subtipoRadioterapia = subtipoRadioterapia[random.randint(0,1)]
            self.localizacionRadioterapia = localizacionRadioterapia[random.randint(0,6)]
            self.dosis = str(np.around(np.random.normal(mediaDosis, desviacionDosis, 1), 2)[0])
            self.fechaInicio = fake.date()
            self.fechaFin = fake.date()
        else:
            self.subtipoCirugia = subtipoCirugia[random.randint(0,5)]
            self.fechaInicio = fake.date()

class Farmaco:

    def __init__(self):
        self.nombreFarmaco = farmacos[random.randint(0,31)]

class Reevaluacion:

    def __init__(self):
        self.fechaReevaluacion = fake.date()
        self.estadoReevaluacion = estadoReevaluacion[random.randint(0,4)]
        if(self.estadoReevaluacion == "Progresión"):
            self.localizacionProgresion = localizacionProgresion[random.randint(0,17)]
            self.tipoTratamientoProgresion = tipoTratamientoProgresion[random.randint(0,1)]
        else:
            self.localizacionProgresion = "Null"
            self.tipoTratamientoProgresion = "Null"

class Seguimiento:

    def __init__(self):
        self.fechaSeguimiento = fake.date()
        self.estadoSeguimiento = estadoSeguimiento[random.randint(0,2)]
        if(self.estadoSeguimiento == "Fallecido"):
            self.motivoFallecimiento = motivoFallecimiento[random.randint(0,1)]
            self.fechaFallecimiento = fake.date()
        else:
            self.motivoFallecimiento = "Null"
            self.fechaFallecimiento = "Null"

if __name__ == "__main__":
    main()
