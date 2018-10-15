CREATE FUNCTION plpgsql_call_handler () RETURNS OPAQUE AS '/usr/lib/pgsql/plpgsql.so' LANGUAGE 'C'; CREATE LANGUAGE 'plpgsql' HANDLER plpgsql_call_handler LANCOMPILER 'PL\pgSQL';
CREATE TRUSTED PROCEDURAL LANGUAGE 'plpgsql' HANDLER plpgsql_call_handler VALIDATOR plpgsql_validator;
