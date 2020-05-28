SELECT * FROM "Aval" WHERE id_aval = 52929;
SELECT * FROM "Cliente";
SELECT * FROM PUBLIC."Direccion" WHERE tipo_direccion = 'trabajo'
SELECT * FROM "Telefono_Cliente" WHERE numero_tel = '2411372391';
SELECT * FROM "Telefono_Aval";
SELECT * FROM "users";
SELECT * FROM PUBLIC."users"
SELECT * FROM PUBLIC."Pago" WHERE id_cliente = '1-1-7388-97847';
SELECT * FROM PUBLIC."Trabajo"
SELECT * FROM "CalendarioPagos" WHERE id_cliente = '1-1-7388-97847';
SELECT * FROM "Convenio" WHERE id_cliente = '1-1-7388-97847';
SELECT * FROM "Gestion"
SELECT * FROM "Despacho"
SELECT * FROM "Relacion_Cliente_Aval";
SELECT * FROM "Relacion_Cliente_Telefono";
SELECT * FROM "Telefono_Cliente";
SELECT * FROM "Tipo_Gestion";
SELECT * FROM "Tipo_Gestion_ssl";
SELECT id_aval FROM "Relacion_Cliente_Aval" WHERE id_cliente ='1-1-4857-149716';
SELECT MAX("id_cliente") from "Cliente";



SELECT * FROM "Relacion_Cliente_Aval" WHERE id_aval='52924';
SELECT * FROM "Relacion_Cliente_Aval" WHERE id_aval='52943';
SELECT * FROM "Relacion_Cliente_Aval" WHERE id_cliente = '1-1-4857-149716';
SELECT * FROM PUBLIC."Trabajo" WHERE id_cliente = '1-1-4857-149716';
SELECT * FROM "Cliente" WHERE nombre = 'MARIA SOCORRO RANCA';
SELECT * FROM  "Telefono_Cliente" WHERE id_tel = '617';
SELECT * FROM "Cliente" WHERE "id_cliente" = ()
SELECT * FROM "Aval" WHERE id_aval = '52922';

SELECT nombre,id_cliente FROM "Cliente" WHERE id_cliente = '1-1-4857-153483';
SELECT id_cliente, nombre FROM "Cliente" WHERE id_cliente = '1-1-7388-35163';
SELECT nombre FROM "Cliente" WHERE nombre = 'MARIA SOCORRO RANCA';
SELECT nombre,id_cliente FROM "Cliente" WHERE id_cliente = '1-1-4857-149716';
SELECT id_cliente FROM  "Telefono_Cliente" WHERE numero_tel = 'LILIANA';
SELECT MAX (id_gestion) FROM "Gestion"
SELECT id_telefono FROM "Relacion_Cliente_Telefono" WHERE id_cliente = '1-1-4857-149716';

DELETE  FROM "Relacion_Cliente_Aval" WHERE id_cliente = '1-1-4857-149716';
DELETE  FROM "Cliente" WHERE id_cliente = '0-1-4857-149716';
DELETE  FROM "CalendarioPagos" WHERE id_cliente = '1-1-7388-97847';
DELETE  FROM "Convenio" WHERE id_cliente = '1-1-7388-97847';
DELETE  FROM "Gestion"

DELETE FROM "Telefono_Cliente";
DELETE FROM "Telefono_Aval";
DELETE FROM "Aval";
DELETE FROM PUBLIC."Cliente"
DELETE FROM PUBLIC."users" WHERE id = 6
DELETE FROM PUBLIC."Pago"
DELETE FROM PUBLIC."Direccion"
DELETE FROM PUBLIC."Trabajo"
DELETE FROM "Telefono_Cliente" WHERE id_tel > 53090;
DELETE FROM "Relacion_Cliente_Telefono" WHERE id_telefono > 53090;

SELECT * FROM "Gestionado"
SELECT * FROM "Convenio" 
SELECT * FROM "CalendarioPagos" 

DELETE FROM "Gestion"
DELETE FROM "Convenio"
DELETE FROM "CalendarioPagos"

DELETE FROM "users"


2411372391 
INSERT INTO "Relacion_Cliente_Telefono" values (617,52924,'2019-06-29 17:10:33.081-05','2019-06-29 17:10:33.081-05');
INSERT INTO "Relacion_Cliente_Aval" values ('1-1-4857-144972',52924,'2019-06-29 17:10:33.081-05','2019-06-29 17:10:33.081-05');
INSERT INTO "Relacion_Cliente_Aval" values ('1-1-4857-163375',52924,'2019-06-29 17:10:33.081-05','2019-06-29 17:10:33.081-05');
INSERT INTO "Relacion_Cliente_Aval" values ('1-1-7388-35163',52924,'2019-06-29 17:10:33.081-05','2019-06-29 17:10:33.081-05');
INSERT INTO "Relacion_Cliente_Aval" values ('1-1-4857-163375',52924,'2019-06-29 17:10:33.081-05','2019-06-29 17:10:33.081-05');
INSERT INTO "Relacion_Cliente_Aval" values ('1-1-4857-149716',52943,'2019-06-29 17:10:33.081-05','2019-06-29 17:10:33.081-05');
INSERT INTO "Relacion_Cliente_Aval" values ('1-1-4857-149716',52923,'2019-06-29 17:10:33.081-05','2019-06-29 17:10:33.081-05');
INSERT INTO public."Telefono_Cliente"( id_cliente, numero_tel, created_at, updated_at) VALUES ('1-39-2728-11873','2411372391', '2019-06-29 17:10:33.081-05', '2019-06-29 17:10:33.081-05');
INSERT INTO public."Direccion"(id_cliente, cuadrante, zona_geo, direccion, num_ext, num_int, tipo_direccion, cp, colonia, poblacion, estado, created_at, updated_at) VALUES ('1-1-4857-149716', 'cuadrante2', 'zona2', 'direccion2', 'num2', 'num2', 'casa', 24167, 'colonia2', 'pobla2', 'estado2', '2019-06-27 19:27:02.189-05', '2019-06-27 19:27:02.189-05');
INSERT INTO public."Aval"(id_direccion, id_cliente, nombre_aval, created_at, updated_at) VALUES ((select id_direccion from "Aval" where id_cliente='1-1-4857-149716'), '1-1-4857-149716', 'aval2','2019-06-27 19:27:02.189-05','2019-06-27 19:27:02.189-05');


INSERT INTO public."Gestionado"(nombre)VALUES ( 'otro');
INSERT INTO public."Gestion"(id_gestion,id_usuario,id_cliente)VALUES (1,5,'1-1-4857-149716');
DELETE FROM "Tipo_Gestion_ssl";

select "Gestion"."folioGen","CalendarioPagos".comentario,"CalendarioPagos".id_calendario, "CalendarioPagos".pagado,
substr(concat('',"CalendarioPagos".fecha_pago_esperada),0,17)
from "Gestion" inner join "Convenio" 
on "Gestion".id_gestion="Convenio".id_gestion 
inner join "CalendarioPagos" on "CalendarioPagos".folio = "Convenio".id_convenio 
where "Gestion"."folioGen" is not null AND "CalendarioPagos".id_cliente = '1-1-4857-149716';

CREATE OR REPLACE FUNCTION actualizaConvenioEstado()
  RETURNS trigger AS
$BODY$
BEGIN
	UPDATE "Convenio" SET convenio_estado=false WHERE id_cliente=NEW.id_cliente AND id_convenio!=NEW.id_convenio;
	RETURN NEW;
END;
$BODY$ LANGUAGE plpgsql;

CREATE TRIGGER actualizaConvenioEstado
    AFTER INSERT ON "Convenio"
    FOR EACH ROW
    EXECUTE PROCEDURE actualizaConvenioEstado();
	
	
	
SELECT * FROM "Convenio" WHERE id_cliente = '1-1-4857-149716' AND convenio_estado = TRUE	