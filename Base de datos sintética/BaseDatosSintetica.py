#!c:/Python3.9/python.exe
# prueba.py

import sys
import random
from datetime import date
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
tipoFGFR = ["Amplificado", "No amplificado", "Mutación"]
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

def main():
	miConexion, cur = establecerConexionBase()
	for x in range(6):
		paciente = Paciente()
		insertarPacienteBase(cur, paciente)
		miConexion.commit()

	miConexion.close()

def establecerConexionBase():
	miConexion = mysql.connector.connect( host='localhost', user= 'root', passwd='', db='prueba' )
	return miConexion, miConexion.cursor()	

def insertarPacienteBase(cur, paciente):
	paciente.crearDatosPaciente()
	cur.execute( "INSERT INTO pacientes (nombre, apellidos, sexo, raza, nacimiento, profesion, fumador, bebedor, ultima_modificacion, carcinogenos) VALUES ('"+paciente.nombre+"','"+paciente.apellidos+"','"+paciente.sexo+"','"+paciente.raza+"','"+paciente.nacimiento+"','"+paciente.profesion+"','"+paciente.fumador+"','"+paciente.bebedor+"','"+paciente.ultimaModificacion+"','"+paciente.carcinogenos+"');" )

def insertarEnfermedad(cur, enfermedad):
	enfermedad.crearDatosEnfermedad()
	cur.execute("INSERT INTO enfermedades (id_paciente, fecha_primera_consulta, fecha_diagnostico, )")

class Paciente:

	def __init__(self):
		self.fake = Faker('es_ES')

	def crearDatosPaciente(self):
		self.nombre = self.fake.first_name()
		self.apellidos = self.fake.last_name()
		self.sexo = sexo[random.randint(0,1)]
		self.raza = raza[random.randint(0,3)]
		self.nacimiento = self.fake.date()
		self.profesion = profesion[random.randint(0,8)]
		self.fumador = fumador[random.randint(0,3)]
		self.bebedor = bebedor[random.randint(0,3)]
		self.carcinogenos = carcinogenos[random.randint(0,1)]
		self.ultimaModificacion = str(date.today())

class Enfermedad:

	def crearDatosEnfermedad(self):
		self.afectacion = afectacion[random.randint(0,1)]
		self.M = M[random.randint(0,3)]["0", "1a", "1b", "1c"]
		self.numAfectacion =  numAfectacion[random.randint(0,3)]
		self.TNM = TNM[random.randint(0,10)]
		self.tipoMuestra = tipoMuestra[random.randint(0,2)]
		self.tipoHistologia = tipoHistologia[random.randint(0,5)]
		self.subtipoHistologia = subtipoHistologia[random.randint(0,7)]
		self.gradoHistologia = gradoHistologia[random.randint(0,3)]

class Sintoma:

	def crearDatosSitnoma(self):
		self.tipoSintoma = tipoSintoma[random.randint(0,10)]

class Metastasis:

	def crearDatosMetastasis(self):
		self.tipoMetastasis = tipoMetastasis[random.randint(0,17)]

class Biomarcador:

	def crearBiomarcador(self):
		self.nombreBiomarcador = nombreBiomarcador[random.randint(0,13)]
		if(self.nombreBiomarcador == "NGS"):
			self.tipo = tipoNGS[random.randint(0,1)]
			self.subtipo = subtipoNGS[random.randint(0,4)]
		elif(self.nombreBiomarcador == "PDL1"):
			self.tipo = tipoPDL1[random.randint(0,1)]
			self.subtipo = subtipoPDL1[random.randint(0,2)]
		elif(self.nombreBiomarcador == "EGFR"):
			self.tipo = tipoEGFR[random.randint(0,4)]
		elif(self.nombreBiomarcador == "ALK"):
			self.tipo = tipoALK[random.randint(0,2)]
			if(self.tipo == "Traslocado"):
				self.subtipo = subtipoALK[0]
		elif(self.nombreBiomarcador == "ROS1"):
			self.tipo = tipoROS1[random.randint(0,1)]
		elif(self.nombreBiomarcador == "KRAS"):
			self.tipo = tipoKRAS[random.randint(0,1)]
			if(self.tipo == "Mutado"):
				self.subtipo = subtipoKRAS[random.randint(0,1)]
		elif(self.nombreBiomarcador == "BRAF"):
			self.tipo = tipoBRAF[random.randint(0,4)]
			if(self.tipo == "Mutado"):
				self.subtipo = subtipoBRAF[random.randint(0,1)]
		elif(self.nombreBiomarcador == "HER2"):
			self.tipo = tipoHER2[random.randint(0,2)]
		elif(self.nombreBiomarcador == "NTRK"):
			self.tipo = tipoNTRK[random.randint(0,2)]
			self.subtipo = subtipoNTRK[random.randint(0,2)]
		elif(self.nombreBiomarcador == "FGFR"):
			self.tipo = tipoFGFR[random.randint(0,2)]
		elif(self.nombreBiomarcador == "MET"):
			self.tipo = tipoMET[random.randint(0,3)]
		elif(self.nombreBiomarcador == "Pl3K"):
			self.tipo = tipoPl3K[random.randint(0,1)]
		elif(self.nombreBiomarcador == "TMB"):
			self.tipo = tipoTMB[random.randint(0,1)]

class PruebaRealizada:

	def crearPruebaRealizada(self):
		self.tipoPrueba = tipoPrueba[random.randint(0,8)] 

class TecnicaRealizada:

	def crearTecnicaRealizada(self):
		self.tipoTecnica = tipoTecnica[random.randint(0,5)] 

class OtroTumor:

	def crearOtroTumor(self):
		self.tipoOtroTumor = tipoOtroTumor[random.randint(0,12)] 

class AntecedenteMedico:

	def crearAntecedenteMedico(self):
		self.tipoAntecedenteMedico = tipoAntecedenteMedico[random.randint(0,9)] 

class AntecedenteOncologico:

	def crearAntecedenteOncologico(self):
		self.tipoAntecedenteOncologico = tipoAntecedenteOncologico[random.randint(0,10)] 

class AntecedenteFamiliar:

	def crearAntecedenteMedico(self):
		self.tipoAntecedenteFamiliar = tipoAntecedenteMedico[random.randint(0,3)] 

class EnfermedadFamiliar:

	def crearEnfermedadFamilia(self):
		self.tipoAntecedenteFamiliar = tipoAntecedenteFamiliar[random.randint(0,10)]

class Tratamiento:

	def crearTratamiento(self):
		self.tipoTratamiento = tipoTratamiento[random.randint(0,2)]
		if(self.tipoTratamiento == "Quimioterapia"):
			self.subtipoQuimioterapia = subtipoQuimioterapia[random.randint(0,2)]
			self.ensayoClinico = random.randint(0,1)
			if(self.ensayoClinico == 1):
				self.tipoEnsayoClinico = tipoEnsayoClinico[random.randint(0,1)]
				self.ensayoClinicoFase = random.randint(1,4)
			self.tratamientoAcceso = random.randint(0,1)
			self.tratamientoFuera = random.randint(0,1)
			self.medicacionExtranjera = random.randint(0,1)
			self.esquema = esquema[random.randint(0,1)]
			self.modoAdministracion = modoAdministracion[random.randint(0,1)]
			self.tipoFarmaco = tipoFarmaco[random.randint(0,7)]
		elif(self.tipoTratamiento == "Radioterapia"):
			self.subtipoRadioterapia = subtipoRadioterapia[random.randint(0,1)]
			self.localizacionRadioterapia = localizacionRadioterapia[random.randint(0,6)]
		else:
			self.subtipoCirugia = subtipoCirugia[random.randint(0,5)]

class Farmaco:

	def crearFarmaco(self):
		self.farmaco = farmacos[random.randint(0,31)]

class Reevaluacion:

	def crearReevaluacion(self):
		self.estadoReevaluacion = estadoReevaluacion[random.randint(0,4)]
		if(self.estadoReevaluacion == "Progresión"):
			self.localizacionProgresion = localizacionProgresion[random.randint(0,17)]
			self.tipoTratamientoProgresion = tipoTratamientoProgresion[random.randint(0,1)]

class Seguimiento:

	def crearSeguimiento(self):
		self.estadoSeguimiento = estadoSeguimiento[random.randint(0,2)]
		if(self.estadoSeguimiento == "Fallecido"):
			self.motivoFallecimiento = motivoFallecimiento[random.randint(0,1)]

if __name__ == "__main__":
    main()
