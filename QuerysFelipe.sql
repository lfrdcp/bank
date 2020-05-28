//se obtiene el id del cliente en base a su nombre
$variableCliente=select id_cliente from "Cliente" where nombre=$variableNombre;

//se obtiene el nombre del cliente en base a su id::conservar este id para las posteriores consultas
$variableCliente=select nombre from "Cliente" where nombre=$variableId;


//se obtiene el idDelCliente del cliente en base a su numtel
$variableCliente=select id_cliente from "Telefono_Cliente" where numero_tel=$variableNumTel;
hacer foreach de estos y mostrar sus datos solicitados haciendo la consulta de los
atributos según el dato requerido con su id especifica, lo cual se muestra en los siguientes ejemplos


//es importante siempre obtener el id del cliente para hacer la consulta de los demás datos en 
las otras tablas y sacar sus atributos correspondientes

//para mostrar el número de cliente
$vectorDeNumeros=select numero_tel from "Telefono_Cliente" where id_cliente=$varibaleCliente;
//en un foreach guardar sus numeros, estos siempre son 4, dada la interfaz
foreach $vectorDeNumeros as $vector
mostrar $vector en blade/vista/html
//mostrar su aval
$aval=select nombre_aval from "Aval" where id_cliente=$variableCliente;
//mostrar su saldo total
$saldoTotal=select total from "Saldo" where id_cliente=$variableCliente;
//mostrar encargado
$encargado=select encargado from "Cliente" where id_cliente=$variableCliente;


select id_cliente,nombre, rfc, gerencia,encargado,clasificacion from "Cliente" where id_cliente=$idCliente;
select direccion,num_ext,num_int,cp,colonia,poblacion,estado from "Direccion" where id_cliente=$idCliente;



select clasificacion,atraso_max,saldo,moratorios,total, dia_de_pago,dia_de_pago2,fecha_pago_ultimo,importe_pago_ultimo from "Pago" where id_cliente=$idCliente;



select num_trabajo from "Trabajo" where id_cliente=$idCliente;
select nombre_aval from "Aval" where id_cliente=$idCliente;
