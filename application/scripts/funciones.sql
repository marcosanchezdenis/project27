CREATE OR REPLACE FUNCTION getNombreSMByPregSMID(pregsmID int4) RETURNS VARCHAR AS $$
	DECLARE
		nom VARCHAR;
	BEGIN
		SELECT nombre INTO nom
		FROM pregunta_sm
		WHERE pregunta_sm_id = pregsmID;

		RETURN nom;
	END;
$$ LANGUAGE plpgsql;

CREATE OR REPLACE FUNCTION getValorByEscalaValorID(escvalID int4) RETURNS INT AS $$
	DECLARE
		val INT;
	BEGIN
		SELECT valor INTO val
		FROM escala_valor
		WHERE escala_valor_id = escvalID;

		RETURN val;
	END;
$$ LANGUAGE plpgsql;

CREATE OR REPLACE FUNCTION getDescByEscalaValorID(escvalID int4) RETURNS VARCHAR AS $$
	DECLARE
		descr VARCHAR;
	BEGIN
		SELECT descripcion INTO descr
		FROM escala_valor
		WHERE escala_valor_id = escvalID;

		RETURN descr;
	END;
$$ LANGUAGE plpgsql;
