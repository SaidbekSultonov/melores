PGDMP                         z            orginal_new_db %   10.15 (Ubuntu 10.15-0ubuntu0.18.04.1) %   10.15 (Ubuntu 10.15-0ubuntu0.18.04.1) l   �           0    0    ENCODING    ENCODING        SET client_encoding = 'UTF8';
                       false            �           0    0 
   STDSTRINGS 
   STDSTRINGS     (   SET standard_conforming_strings = 'on';
                       false            �           0    0 
   SEARCHPATH 
   SEARCHPATH     8   SELECT pg_catalog.set_config('search_path', '', false);
                       false            �           1262    16385    orginal_new_db    DATABASE     �   CREATE DATABASE orginal_new_db WITH TEMPLATE = template0 ENCODING = 'UTF8' LC_COLLATE = 'en_US.UTF-8' LC_CTYPE = 'en_US.UTF-8';
    DROP DATABASE orginal_new_db;
             postgres    false                        2615    2200    public    SCHEMA        CREATE SCHEMA public;
    DROP SCHEMA public;
             postgres    false            �           0    0    SCHEMA public    COMMENT     6   COMMENT ON SCHEMA public IS 'standard public schema';
                  postgres    false    3                        3079    13043    plpgsql 	   EXTENSION     ?   CREATE EXTENSION IF NOT EXISTS plpgsql WITH SCHEMA pg_catalog;
    DROP EXTENSION plpgsql;
                  false            �           0    0    EXTENSION plpgsql    COMMENT     @   COMMENT ON EXTENSION plpgsql IS 'PL/pgSQL procedural language';
                       false    1                       1255    17726    user_add_last_id()    FUNCTION       CREATE FUNCTION public.user_add_last_id() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
begin
      insert into userlog 
      (company_id, last_id, type, chat_id)
        values
      (NEW.company_id, NEW.id, 5, NEW.chat_id);
      return NEW;    
    end;
$$;
 )   DROP FUNCTION public.user_add_last_id();
       public       postgres    false    3    1                       1255    17790    user_ask_last_id()    FUNCTION       CREATE FUNCTION public.user_ask_last_id() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
begin
      insert into userlog 
      (company_id, last_id, type, chat_id)
        values
      (NEW.company_id, NEW.id, 8, NEW.chat_id);
      return NEW;    
    end;
$$;
 )   DROP FUNCTION public.user_ask_last_id();
       public       postgres    false    1    3                       1255    17848    user_family_last_id()    FUNCTION       CREATE FUNCTION public.user_family_last_id() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
    begin
      insert into userlog 
      (company_id, last_id, type, chat_id)
        values
      (NEW.company_id, NEW.id, 4, NEW.chat_id);
      return NEW;    
    end;
  $$;
 ,   DROP FUNCTION public.user_family_last_id();
       public       postgres    false    1    3            ,           1255    18040    user_lang_last_id()    FUNCTION       CREATE FUNCTION public.user_lang_last_id() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
    begin
      insert into userlog 
      (company_id, last_id, type, chat_id)
        values
      (NEW.company_id, NEW.id, 6, NEW.chat_id);
      return NEW;    
    end;
  $$;
 *   DROP FUNCTION public.user_lang_last_id();
       public       postgres    false    3    1                       1255    17808    user_last_last_id()    FUNCTION       CREATE FUNCTION public.user_last_last_id() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
    begin
      insert into userlog 
      (company_id, last_id, type, chat_id)
        values
      (NEW.company_id, NEW.id, 9, NEW.chat_id);
      return NEW;    
    end;
  $$;
 *   DROP FUNCTION public.user_last_last_id();
       public       postgres    false    3    1                       1255    17756    user_our_last_id()    FUNCTION       CREATE FUNCTION public.user_our_last_id() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
begin
      insert into userlog 
      (company_id, last_id, type, chat_id)
        values
      (NEW.company_id, NEW.id, 7, NEW.chat_id);
      return NEW;    
    end;
$$;
 )   DROP FUNCTION public.user_our_last_id();
       public       postgres    false    3    1                       1255    17641    user_study_last_id()    FUNCTION       CREATE FUNCTION public.user_study_last_id() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
    begin
      insert into userlog 
      (company_id, last_id, type, chat_id)
        values
      (NEW.company_id, NEW.id, 1, NEW.chat_id);
      return NEW;    
    end;
  $$;
 +   DROP FUNCTION public.user_study_last_id();
       public       postgres    false    1    3                       1255    17648    user_trip_last_id()    FUNCTION       CREATE FUNCTION public.user_trip_last_id() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
    begin
      insert into userlog 
      (company_id, last_id, type, chat_id)
        values
      (NEW.company_id, NEW.id, 3, NEW.chat_id);
      return NEW;    
    end;
  $$;
 *   DROP FUNCTION public.user_trip_last_id();
       public       postgres    false    1    3                       1255    17646    user_work_last_id()    FUNCTION       CREATE FUNCTION public.user_work_last_id() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
    begin
      insert into userlog 
      (company_id, last_id, type, chat_id)
        values
      (NEW.company_id, NEW.id, 2, NEW.chat_id);
      return NEW;    
    end;
  $$;
 *   DROP FUNCTION public.user_work_last_id();
       public       postgres    false    3    1                       1255    17538    userinforeg()    FUNCTION     
  CREATE FUNCTION public.userinforeg() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
    begin
      insert into inforeg 
      (company_id, user_id, chat_id, status)
        values
      (NEW.company_id, NEW.id, NEW.chat_id, 1);
      return NEW;    
    end;
  $$;
 $   DROP FUNCTION public.userinforeg();
       public       postgres    false    1    3            �            1259    16695    about_us    TABLE     �   CREATE TABLE public.about_us (
    id integer NOT NULL,
    company_id integer,
    text text,
    file character varying(100),
    type smallint,
    tg_file_id character varying(250),
    text_ru character varying(255)
);
    DROP TABLE public.about_us;
       public         postgres    false    3            �            1259    16701    about_us_id_seq    SEQUENCE     �   CREATE SEQUENCE public.about_us_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 &   DROP SEQUENCE public.about_us_id_seq;
       public       postgres    false    3    196            �           0    0    about_us_id_seq    SEQUENCE OWNED BY     C   ALTER SEQUENCE public.about_us_id_seq OWNED BY public.about_us.id;
            public       postgres    false    197            �            1259    16703 	   actionreg    TABLE     �   CREATE TABLE public.actionreg (
    id integer NOT NULL,
    company_id integer,
    chat_id integer,
    step_1 integer,
    step_2 integer,
    order_num integer
);
    DROP TABLE public.actionreg;
       public         postgres    false    3            �            1259    16706    actionreg_id_seq    SEQUENCE     �   CREATE SEQUENCE public.actionreg_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 '   DROP SEQUENCE public.actionreg_id_seq;
       public       postgres    false    3    198            �           0    0    actionreg_id_seq    SEQUENCE OWNED BY     E   ALTER SEQUENCE public.actionreg_id_seq OWNED BY public.actionreg.id;
            public       postgres    false    199            �            1259    16708    additional_aq    TABLE     �   CREATE TABLE public.additional_aq (
    id integer NOT NULL,
    company_id integer NOT NULL,
    user_id integer NOT NULL,
    step_id integer NOT NULL,
    answer text NOT NULL
);
 !   DROP TABLE public.additional_aq;
       public         postgres    false    3            �            1259    16714    additional_aq_id_seq    SEQUENCE     �   CREATE SEQUENCE public.additional_aq_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 +   DROP SEQUENCE public.additional_aq_id_seq;
       public       postgres    false    3    200            �           0    0    additional_aq_id_seq    SEQUENCE OWNED BY     M   ALTER SEQUENCE public.additional_aq_id_seq OWNED BY public.additional_aq.id;
            public       postgres    false    201            �            1259    16716    billing    TABLE     %  CREATE TABLE public.billing (
    id integer NOT NULL,
    company_id integer,
    start_date timestamp(0) without time zone,
    end_date timestamp(0) without time zone,
    billine_date timestamp(0) without time zone,
    type character varying(255),
    status smallint,
    sum integer
);
    DROP TABLE public.billing;
       public         postgres    false    3            �            1259    16719    billing_id_seq    SEQUENCE     �   CREATE SEQUENCE public.billing_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 %   DROP SEQUENCE public.billing_id_seq;
       public       postgres    false    202    3            �           0    0    billing_id_seq    SEQUENCE OWNED BY     A   ALTER SEQUENCE public.billing_id_seq OWNED BY public.billing.id;
            public       postgres    false    203            �            1259    16721    branch    TABLE     �   CREATE TABLE public.branch (
    id integer NOT NULL,
    title character varying(255) NOT NULL,
    status smallint,
    title_ru character varying(255)
);
    DROP TABLE public.branch;
       public         postgres    false    3            �            1259    16727    branch_id_seq    SEQUENCE     �   CREATE SEQUENCE public.branch_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 $   DROP SEQUENCE public.branch_id_seq;
       public       postgres    false    204    3            �           0    0    branch_id_seq    SEQUENCE OWNED BY     ?   ALTER SEQUENCE public.branch_id_seq OWNED BY public.branch.id;
            public       postgres    false    205            �            1259    16729    branch_professions    TABLE     v   CREATE TABLE public.branch_professions (
    id integer NOT NULL,
    branch_id integer,
    profession_id integer
);
 &   DROP TABLE public.branch_professions;
       public         postgres    false    3            �            1259    16732    branch_professions_id_seq    SEQUENCE     �   CREATE SEQUENCE public.branch_professions_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 0   DROP SEQUENCE public.branch_professions_id_seq;
       public       postgres    false    206    3            �           0    0    branch_professions_id_seq    SEQUENCE OWNED BY     W   ALTER SEQUENCE public.branch_professions_id_seq OWNED BY public.branch_professions.id;
            public       postgres    false    207            �            1259    16734    chat    TABLE     �   CREATE TABLE public.chat (
    id integer NOT NULL,
    type smallint,
    message text,
    datetime timestamp(0) without time zone,
    company_id integer,
    chat_id integer
);
    DROP TABLE public.chat;
       public         postgres    false    3            �            1259    16740    chat_id_seq    SEQUENCE     �   CREATE SEQUENCE public.chat_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 "   DROP SEQUENCE public.chat_id_seq;
       public       postgres    false    3    208            �           0    0    chat_id_seq    SEQUENCE OWNED BY     ;   ALTER SEQUENCE public.chat_id_seq OWNED BY public.chat.id;
            public       postgres    false    209            �            1259    16742    company    TABLE     _  CREATE TABLE public.company (
    id integer NOT NULL,
    title character varying(100) NOT NULL,
    unique_title character varying(110) NOT NULL,
    bot_token character varying(255) NOT NULL,
    generate_key character varying(255) DEFAULT NULL::character varying,
    send_id character varying(50),
    is_active integer,
    branch_id integer
);
    DROP TABLE public.company;
       public         postgres    false    3            �            1259    16749    company_config    TABLE     q   CREATE TABLE public.company_config (
    id integer NOT NULL,
    company_id integer,
    pdf_status smallint
);
 "   DROP TABLE public.company_config;
       public         postgres    false    3            �            1259    16752    company_config_id_seq    SEQUENCE     �   CREATE SEQUENCE public.company_config_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 ,   DROP SEQUENCE public.company_config_id_seq;
       public       postgres    false    3    211            �           0    0    company_config_id_seq    SEQUENCE OWNED BY     O   ALTER SEQUENCE public.company_config_id_seq OWNED BY public.company_config.id;
            public       postgres    false    212            �            1259    16754    company_id_seq    SEQUENCE     �   CREATE SEQUENCE public.company_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 %   DROP SEQUENCE public.company_id_seq;
       public       postgres    false    3    210            �           0    0    company_id_seq    SEQUENCE OWNED BY     A   ALTER SEQUENCE public.company_id_seq OWNED BY public.company.id;
            public       postgres    false    213            �            1259    16756    company_info    TABLE     #  CREATE TABLE public.company_info (
    id integer NOT NULL,
    company_id integer NOT NULL,
    file_id character varying(255) DEFAULT NULL::character varying,
    description text,
    type character varying(20) DEFAULT NULL::character varying,
    is_begin integer,
    status integer
);
     DROP TABLE public.company_info;
       public         postgres    false    3            �            1259    16764    company_info_id_seq    SEQUENCE     �   CREATE SEQUENCE public.company_info_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 *   DROP SEQUENCE public.company_info_id_seq;
       public       postgres    false    3    214            �           0    0    company_info_id_seq    SEQUENCE OWNED BY     K   ALTER SEQUENCE public.company_info_id_seq OWNED BY public.company_info.id;
            public       postgres    false    215            �            1259    16766    company_language    TABLE     �   CREATE TABLE public.company_language (
    id integer NOT NULL,
    company_id integer NOT NULL,
    language_id integer NOT NULL,
    status integer
);
 $   DROP TABLE public.company_language;
       public         postgres    false    3            �            1259    16769    company_language_id_seq    SEQUENCE     �   CREATE SEQUENCE public.company_language_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 .   DROP SEQUENCE public.company_language_id_seq;
       public       postgres    false    3    216            �           0    0    company_language_id_seq    SEQUENCE OWNED BY     S   ALTER SEQUENCE public.company_language_id_seq OWNED BY public.company_language.id;
            public       postgres    false    217            �            1259    16771    company_profession    TABLE     �   CREATE TABLE public.company_profession (
    id integer NOT NULL,
    company_id integer NOT NULL,
    profession_id integer NOT NULL,
    status integer
);
 &   DROP TABLE public.company_profession;
       public         postgres    false    3            �            1259    16774    company_profession_id_seq    SEQUENCE     �   CREATE SEQUENCE public.company_profession_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 0   DROP SEQUENCE public.company_profession_id_seq;
       public       postgres    false    218    3            �           0    0    company_profession_id_seq    SEQUENCE OWNED BY     W   ALTER SEQUENCE public.company_profession_id_seq OWNED BY public.company_profession.id;
            public       postgres    false    219            �            1259    16776    company_skills    TABLE     �   CREATE TABLE public.company_skills (
    id integer NOT NULL,
    company_id integer NOT NULL,
    skill_id integer NOT NULL,
    status integer,
    profession_id integer
);
 "   DROP TABLE public.company_skills;
       public         postgres    false    3            �            1259    16779    company_programm_id_seq    SEQUENCE     �   CREATE SEQUENCE public.company_programm_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 .   DROP SEQUENCE public.company_programm_id_seq;
       public       postgres    false    220    3            �           0    0    company_programm_id_seq    SEQUENCE OWNED BY     Q   ALTER SEQUENCE public.company_programm_id_seq OWNED BY public.company_skills.id;
            public       postgres    false    221            �            1259    16781    company_settings    TABLE     +  CREATE TABLE public.company_settings (
    id integer NOT NULL,
    company_id integer NOT NULL,
    step integer,
    title_uz character varying(255) DEFAULT NULL::character varying,
    title_ru character varying(255) DEFAULT NULL::character varying,
    order_step integer,
    status integer
);
 $   DROP TABLE public.company_settings;
       public         postgres    false    3            �            1259    16789    company_settings_id_seq    SEQUENCE     �   CREATE SEQUENCE public.company_settings_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 .   DROP SEQUENCE public.company_settings_id_seq;
       public       postgres    false    3    222            �           0    0    company_settings_id_seq    SEQUENCE OWNED BY     S   ALTER SEQUENCE public.company_settings_id_seq OWNED BY public.company_settings.id;
            public       postgres    false    223            �            1259    16791    date_formats    TABLE     �   CREATE TABLE public.date_formats (
    id integer NOT NULL,
    name character varying(255),
    status smallint,
    example_view character varying(255)
);
     DROP TABLE public.date_formats;
       public         postgres    false    3            �            1259    16797    date_formats_id_seq    SEQUENCE     �   CREATE SEQUENCE public.date_formats_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 *   DROP SEQUENCE public.date_formats_id_seq;
       public       postgres    false    3    224            �           0    0    date_formats_id_seq    SEQUENCE OWNED BY     K   ALTER SEQUENCE public.date_formats_id_seq OWNED BY public.date_formats.id;
            public       postgres    false    225            �            1259    16799    friends    TABLE     �   CREATE TABLE public.friends (
    id integer NOT NULL,
    company_id integer,
    chat_id integer,
    friend_chat_id integer
);
    DROP TABLE public.friends;
       public         postgres    false    3            �            1259    16802    friends_id_seq    SEQUENCE     �   CREATE SEQUENCE public.friends_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 %   DROP SEQUENCE public.friends_id_seq;
       public       postgres    false    3    226            �           0    0    friends_id_seq    SEQUENCE OWNED BY     A   ALTER SEQUENCE public.friends_id_seq OWNED BY public.friends.id;
            public       postgres    false    227            �            1259    16804    inforeg    TABLE     �   CREATE TABLE public.inforeg (
    id integer NOT NULL,
    company_id integer NOT NULL,
    user_id integer,
    chat_id integer,
    status integer
);
    DROP TABLE public.inforeg;
       public         postgres    false    3            �            1259    16807    inforeg_id_seq    SEQUENCE     �   CREATE SEQUENCE public.inforeg_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 %   DROP SEQUENCE public.inforeg_id_seq;
       public       postgres    false    3    228            �           0    0    inforeg_id_seq    SEQUENCE OWNED BY     A   ALTER SEQUENCE public.inforeg_id_seq OWNED BY public.inforeg.id;
            public       postgres    false    229            �            1259    16809    language    TABLE     �   CREATE TABLE public.language (
    id integer NOT NULL,
    title_uz character varying(255) DEFAULT NULL::character varying,
    title_ru character varying(255) DEFAULT NULL::character varying,
    status integer
);
    DROP TABLE public.language;
       public         postgres    false    3            �            1259    16817    language_id_seq    SEQUENCE     �   CREATE SEQUENCE public.language_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 &   DROP SEQUENCE public.language_id_seq;
       public       postgres    false    230    3            �           0    0    language_id_seq    SEQUENCE OWNED BY     C   ALTER SEQUENCE public.language_id_seq OWNED BY public.language.id;
            public       postgres    false    231            �            1259    16819    measure_step    TABLE     �   CREATE TABLE public.measure_step (
    id integer NOT NULL,
    title_uz character varying(255) NOT NULL,
    title_ru character varying(255) NOT NULL,
    status integer NOT NULL
);
     DROP TABLE public.measure_step;
       public         postgres    false    3            �            1259    16825    measure_step_id_seq    SEQUENCE     �   CREATE SEQUENCE public.measure_step_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 *   DROP SEQUENCE public.measure_step_id_seq;
       public       postgres    false    232    3            �           0    0    measure_step_id_seq    SEQUENCE OWNED BY     K   ALTER SEQUENCE public.measure_step_id_seq OWNED BY public.measure_step.id;
            public       postgres    false    233            �            1259    16827    meeting    TABLE     �   CREATE TABLE public.meeting (
    id integer NOT NULL,
    company_id integer,
    datetime timestamp(0) without time zone,
    chat_id integer,
    description text,
    reminder_time timestamp(0) without time zone
);
    DROP TABLE public.meeting;
       public         postgres    false    3            �            1259    16833    meeting_id_seq    SEQUENCE     �   CREATE SEQUENCE public.meeting_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 %   DROP SEQUENCE public.meeting_id_seq;
       public       postgres    false    3    234            �           0    0    meeting_id_seq    SEQUENCE OWNED BY     A   ALTER SEQUENCE public.meeting_id_seq OWNED BY public.meeting.id;
            public       postgres    false    235            �            1259    16835 	   migration    TABLE     g   CREATE TABLE public.migration (
    version character varying(180) NOT NULL,
    apply_time integer
);
    DROP TABLE public.migration;
       public         postgres    false    3            �            1259    16838    new_test    TABLE     �   CREATE TABLE public.new_test (
    id integer NOT NULL,
    name character varying(32),
    date date,
    "time" character varying(50)
);
    DROP TABLE public.new_test;
       public         postgres    false    3            �            1259    16841    new_test_id_seq    SEQUENCE     �   CREATE SEQUENCE public.new_test_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 &   DROP SEQUENCE public.new_test_id_seq;
       public       postgres    false    237    3            �           0    0    new_test_id_seq    SEQUENCE OWNED BY     C   ALTER SEQUENCE public.new_test_id_seq OWNED BY public.new_test.id;
            public       postgres    false    238            �            1259    16843    notification    TABLE     �   CREATE TABLE public.notification (
    id integer NOT NULL,
    company_id integer,
    datetime timestamp(0) without time zone,
    message text,
    file_id integer,
    type_col integer,
    is_referal_link smallint
);
     DROP TABLE public.notification;
       public         postgres    false    3            �            1259    16849    notification_id_seq    SEQUENCE     �   CREATE SEQUENCE public.notification_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 *   DROP SEQUENCE public.notification_id_seq;
       public       postgres    false    239    3            �           0    0    notification_id_seq    SEQUENCE OWNED BY     K   ALTER SEQUENCE public.notification_id_seq OWNED BY public.notification.id;
            public       postgres    false    240            �            1259    16851    ourwork    TABLE     �  CREATE TABLE public.ourwork (
    id integer NOT NULL,
    company_id integer NOT NULL,
    user_id integer,
    about_us character varying(255) DEFAULT NULL::character varying,
    human_name character varying(150) DEFAULT NULL::character varying,
    human_work character varying(100) DEFAULT NULL::character varying,
    human_phone character varying(50),
    chat_id integer DEFAULT 11
);
    DROP TABLE public.ourwork;
       public         postgres    false    3            �            1259    16860    ourwork_id_seq    SEQUENCE     �   CREATE SEQUENCE public.ourwork_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 %   DROP SEQUENCE public.ourwork_id_seq;
       public       postgres    false    241    3            �           0    0    ourwork_id_seq    SEQUENCE OWNED BY     A   ALTER SEQUENCE public.ourwork_id_seq OWNED BY public.ourwork.id;
            public       postgres    false    242            �            1259    16862 
   profession    TABLE     �   CREATE TABLE public.profession (
    id integer NOT NULL,
    title_uz character varying(255) DEFAULT NULL::character varying,
    title_ru character varying(255) DEFAULT NULL::character varying,
    status integer
);
    DROP TABLE public.profession;
       public         postgres    false    3            �            1259    16870    profession_id_seq    SEQUENCE     �   CREATE SEQUENCE public.profession_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 (   DROP SEQUENCE public.profession_id_seq;
       public       postgres    false    3    243            �           0    0    profession_id_seq    SEQUENCE OWNED BY     G   ALTER SEQUENCE public.profession_id_seq OWNED BY public.profession.id;
            public       postgres    false    244            �            1259    16872    profession_skills    TABLE     t   CREATE TABLE public.profession_skills (
    id integer NOT NULL,
    profession_id integer,
    skill_id integer
);
 %   DROP TABLE public.profession_skills;
       public         postgres    false    3            �            1259    16875    profession_skills_id_seq    SEQUENCE     �   CREATE SEQUENCE public.profession_skills_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 /   DROP SEQUENCE public.profession_skills_id_seq;
       public       postgres    false    245    3            �           0    0    profession_skills_id_seq    SEQUENCE OWNED BY     U   ALTER SEQUENCE public.profession_skills_id_seq OWNED BY public.profession_skills.id;
            public       postgres    false    246            �            1259    16877    profile    TABLE     Z  CREATE TABLE public.profile (
    id integer NOT NULL,
    firstname character varying(255) NOT NULL,
    lastname character varying(255) NOT NULL,
    email character varying(255) NOT NULL,
    company_id integer NOT NULL,
    password_hash character varying(255) NOT NULL,
    created_at integer,
    updated_at integer,
    user_id integer
);
    DROP TABLE public.profile;
       public         postgres    false    3            �            1259    16883    profile_id_seq    SEQUENCE     �   CREATE SEQUENCE public.profile_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 %   DROP SEQUENCE public.profile_id_seq;
       public       postgres    false    247    3            �           0    0    profile_id_seq    SEQUENCE OWNED BY     A   ALTER SEQUENCE public.profile_id_seq OWNED BY public.profile.id;
            public       postgres    false    248            �            1259    16885    skills    TABLE     �   CREATE TABLE public.skills (
    id integer NOT NULL,
    profession_id integer,
    title character varying(255) DEFAULT NULL::character varying,
    status integer,
    title_ru character varying(255)
);
    DROP TABLE public.skills;
       public         postgres    false    3            �            1259    16892    programm_id_seq    SEQUENCE     �   CREATE SEQUENCE public.programm_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 &   DROP SEQUENCE public.programm_id_seq;
       public       postgres    false    3    249            �           0    0    programm_id_seq    SEQUENCE OWNED BY     A   ALTER SEQUENCE public.programm_id_seq OWNED BY public.skills.id;
            public       postgres    false    250            �            1259    16894 	   reference    TABLE     {  CREATE TABLE public.reference (
    id integer NOT NULL,
    company_id integer NOT NULL,
    title character varying(255) NOT NULL,
    date timestamp(0) without time zone NOT NULL,
    description text,
    oxvat integer,
    type smallint,
    file character varying(255),
    status smallint,
    title_ru character varying(255),
    description_ru character varying(255)
);
    DROP TABLE public.reference;
       public         postgres    false    3            �            1259    16900    reference_id_seq    SEQUENCE     �   CREATE SEQUENCE public.reference_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 '   DROP SEQUENCE public.reference_id_seq;
       public       postgres    false    3    251            �           0    0    reference_id_seq    SEQUENCE OWNED BY     E   ALTER SEQUENCE public.reference_id_seq OWNED BY public.reference.id;
            public       postgres    false    252            �            1259    16902    user    TABLE     �  CREATE TABLE public."user" (
    id integer NOT NULL,
    username character varying(255) NOT NULL,
    email character varying(255) NOT NULL,
    password_hash character varying(255) NOT NULL,
    last_visited integer,
    status integer NOT NULL,
    auth_key character varying(32),
    password_reset_token character varying(255),
    verification_token character varying(255),
    created_at integer,
    date_format_id integer
);
    DROP TABLE public."user";
       public         postgres    false    3            �            1259    16908    user_add    TABLE     �  CREATE TABLE public.user_add (
    id integer NOT NULL,
    company_id integer NOT NULL,
    user_id integer,
    work_trip character varying(100),
    user_army character varying(100),
    user_court character varying(100),
    car character varying(100) DEFAULT NULL::character varying,
    car_grade character varying(2) DEFAULT NULL::character varying,
    chat_id integer DEFAULT 11
);
    DROP TABLE public.user_add;
       public         postgres    false    3            �            1259    16913    user_add_id_seq    SEQUENCE     �   CREATE SEQUENCE public.user_add_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 &   DROP SEQUENCE public.user_add_id_seq;
       public       postgres    false    254    3            �           0    0    user_add_id_seq    SEQUENCE OWNED BY     C   ALTER SEQUENCE public.user_add_id_seq OWNED BY public.user_add.id;
            public       postgres    false    255                        1259    16915    user_ask    TABLE     }  CREATE TABLE public.user_ask (
    id integer NOT NULL,
    company_id integer NOT NULL,
    user_id integer,
    new_salary character varying(100) DEFAULT NULL::character varying,
    work_year character varying(70) DEFAULT NULL::character varying,
    after_work character varying(70) DEFAULT NULL::character varying,
    meet_work character varying(70) DEFAULT NULL::character varying,
    collectiv_work character varying(255) DEFAULT NULL::character varying,
    meet_parent character varying(70) DEFAULT NULL::character varying,
    healthy character varying(255) DEFAULT NULL::character varying,
    chat_id integer DEFAULT 11
);
    DROP TABLE public.user_ask;
       public         postgres    false    3                       1259    16928    user_ask_id_seq    SEQUENCE     �   CREATE SEQUENCE public.user_ask_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 &   DROP SEQUENCE public.user_ask_id_seq;
       public       postgres    false    256    3            �           0    0    user_ask_id_seq    SEQUENCE OWNED BY     C   ALTER SEQUENCE public.user_ask_id_seq OWNED BY public.user_ask.id;
            public       postgres    false    257                       1259    16930    user_family    TABLE     N  CREATE TABLE public.user_family (
    id integer NOT NULL,
    company_id integer NOT NULL,
    user_id integer,
    family_member character varying(50) DEFAULT NULL::character varying,
    member_name character varying(150) DEFAULT NULL::character varying,
    member_birth character varying(50) DEFAULT NULL::character varying,
    member_work character varying(100) DEFAULT NULL::character varying,
    member_phone character varying(100),
    member_live character varying(255) DEFAULT NULL::character varying,
    member_court character varying(100),
    chat_id integer DEFAULT 11
);
    DROP TABLE public.user_family;
       public         postgres    false    3                       1259    16941    user_family_id_seq    SEQUENCE     �   CREATE SEQUENCE public.user_family_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 )   DROP SEQUENCE public.user_family_id_seq;
       public       postgres    false    3    258            �           0    0    user_family_id_seq    SEQUENCE OWNED BY     I   ALTER SEQUENCE public.user_family_id_seq OWNED BY public.user_family.id;
            public       postgres    false    259                       1259    16943    user_id_seq    SEQUENCE     �   CREATE SEQUENCE public.user_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 "   DROP SEQUENCE public.user_id_seq;
       public       postgres    false    3    253            �           0    0    user_id_seq    SEQUENCE OWNED BY     =   ALTER SEQUENCE public.user_id_seq OWNED BY public."user".id;
            public       postgres    false    260                       1259    16945 	   user_lang    TABLE     �   CREATE TABLE public.user_lang (
    id integer NOT NULL,
    company_id integer NOT NULL,
    user_id integer,
    language integer,
    user_speak integer,
    user_write integer,
    user_read integer,
    chat_id integer DEFAULT 11
);
    DROP TABLE public.user_lang;
       public         postgres    false    3                       1259    16948    user_lang_id_seq    SEQUENCE     �   CREATE SEQUENCE public.user_lang_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 '   DROP SEQUENCE public.user_lang_id_seq;
       public       postgres    false    3    261            �           0    0    user_lang_id_seq    SEQUENCE OWNED BY     E   ALTER SEQUENCE public.user_lang_id_seq OWNED BY public.user_lang.id;
            public       postgres    false    262                       1259    16950    user_pdf    TABLE     �   CREATE TABLE public.user_pdf (
    id integer NOT NULL,
    company_id integer NOT NULL,
    user_id integer,
    chat_id integer,
    doc_name character varying(255) DEFAULT NULL::character varying
);
    DROP TABLE public.user_pdf;
       public         postgres    false    3                       1259    16954    user_pdf_id_seq    SEQUENCE     �   CREATE SEQUENCE public.user_pdf_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 &   DROP SEQUENCE public.user_pdf_id_seq;
       public       postgres    false    263    3            �           0    0    user_pdf_id_seq    SEQUENCE OWNED BY     C   ALTER SEQUENCE public.user_pdf_id_seq OWNED BY public.user_pdf.id;
            public       postgres    false    264            	           1259    16956 
   user_skill    TABLE     �   CREATE TABLE public.user_skill (
    id integer NOT NULL,
    company_id integer NOT NULL,
    user_id integer,
    skill_id integer
);
    DROP TABLE public.user_skill;
       public         postgres    false    3            
           1259    16959    user_prog_id_seq    SEQUENCE     �   CREATE SEQUENCE public.user_prog_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 '   DROP SEQUENCE public.user_prog_id_seq;
       public       postgres    false    265    3            �           0    0    user_prog_id_seq    SEQUENCE OWNED BY     F   ALTER SEQUENCE public.user_prog_id_seq OWNED BY public.user_skill.id;
            public       postgres    false    266                       1259    16961 
   user_study    TABLE     �  CREATE TABLE public.user_study (
    id integer NOT NULL,
    company_id integer NOT NULL,
    user_id integer,
    study_name character varying(100) DEFAULT NULL::character varying,
    study_year character varying(100) DEFAULT NULL::character varying,
    study_field character varying(100) DEFAULT NULL::character varying,
    study_grade character varying(50),
    chat_id integer DEFAULT 11
);
    DROP TABLE public.user_study;
       public         postgres    false    3                       1259    16967    user_study_id_seq    SEQUENCE     �   CREATE SEQUENCE public.user_study_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 (   DROP SEQUENCE public.user_study_id_seq;
       public       postgres    false    267    3            �           0    0    user_study_id_seq    SEQUENCE OWNED BY     G   ALTER SEQUENCE public.user_study_id_seq OWNED BY public.user_study.id;
            public       postgres    false    268                       1259    16969 	   user_trip    TABLE     L  CREATE TABLE public.user_trip (
    id integer NOT NULL,
    company_id integer NOT NULL,
    user_id integer,
    trip_status integer,
    trip_place character varying(255) DEFAULT NULL::character varying,
    trip_year character varying(50) DEFAULT NULL::character varying,
    trip_reason text,
    chat_id integer DEFAULT 11
);
    DROP TABLE public.user_trip;
       public         postgres    false    3                       1259    16977    user_trip_id_seq    SEQUENCE     �   CREATE SEQUENCE public.user_trip_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 '   DROP SEQUENCE public.user_trip_id_seq;
       public       postgres    false    269    3            �           0    0    user_trip_id_seq    SEQUENCE OWNED BY     E   ALTER SEQUENCE public.user_trip_id_seq OWNED BY public.user_trip.id;
            public       postgres    false    270                       1259    16979 	   user_work    TABLE     U  CREATE TABLE public.user_work (
    id integer NOT NULL,
    company_id integer NOT NULL,
    user_id integer,
    work integer,
    work_place character varying(255) DEFAULT NULL::character varying,
    work_year character varying(50) DEFAULT NULL::character varying,
    work_pos text,
    work_out text,
    chat_id integer DEFAULT 11
);
    DROP TABLE public.user_work;
       public         postgres    false    3                       1259    16987    user_work_id_seq    SEQUENCE     �   CREATE SEQUENCE public.user_work_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 '   DROP SEQUENCE public.user_work_id_seq;
       public       postgres    false    3    271            �           0    0    user_work_id_seq    SEQUENCE OWNED BY     E   ALTER SEQUENCE public.user_work_id_seq OWNED BY public.user_work.id;
            public       postgres    false    272                       1259    16989    userinfo    TABLE     �  CREATE TABLE public.userinfo (
    id integer NOT NULL,
    company_id integer NOT NULL,
    profession_id integer,
    chat_id integer,
    username character varying(100) DEFAULT NULL::character varying,
    firstname character varying(100) DEFAULT NULL::character varying,
    fullname character varying(255) DEFAULT NULL::character varying,
    birthday character varying(70) DEFAULT NULL::character varying,
    nationality character varying(70) DEFAULT NULL::character varying,
    birth_place character varying(255) DEFAULT NULL::character varying,
    live_place character varying(255) DEFAULT NULL::character varying,
    live_status character varying(100),
    phone character varying(100),
    mail character varying(100) DEFAULT NULL::character varying,
    marry character varying(100),
    is_active integer,
    app_condition smallint,
    date_application timestamp(0) without time zone,
    is_archive smallint,
    reason_archive text,
    archive_date timestamp(0) without time zone
);
    DROP TABLE public.userinfo;
       public         postgres    false    3                       1259    17003    userinfo_id_seq    SEQUENCE     �   CREATE SEQUENCE public.userinfo_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 &   DROP SEQUENCE public.userinfo_id_seq;
       public       postgres    false    273    3            �           0    0    userinfo_id_seq    SEQUENCE OWNED BY     C   ALTER SEQUENCE public.userinfo_id_seq OWNED BY public.userinfo.id;
            public       postgres    false    274                       1259    17005    userinfo_last    TABLE     �   CREATE TABLE public.userinfo_last (
    id integer NOT NULL,
    company_id integer NOT NULL,
    user_id integer,
    goods text,
    bads text,
    image character varying(255) DEFAULT NULL::character varying,
    chat_id integer DEFAULT 11
);
 !   DROP TABLE public.userinfo_last;
       public         postgres    false    3                       1259    17012    userinfo_last_id_seq    SEQUENCE     �   CREATE SEQUENCE public.userinfo_last_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 +   DROP SEQUENCE public.userinfo_last_id_seq;
       public       postgres    false    275    3            �           0    0    userinfo_last_id_seq    SEQUENCE OWNED BY     M   ALTER SEQUENCE public.userinfo_last_id_seq OWNED BY public.userinfo_last.id;
            public       postgres    false    276                       1259    17014    userlog    TABLE     �   CREATE TABLE public.userlog (
    id integer NOT NULL,
    company_id integer NOT NULL,
    last_id integer,
    chat_id integer,
    type integer
);
    DROP TABLE public.userlog;
       public         postgres    false    3                       1259    17017    userlog_id_seq    SEQUENCE     �   CREATE SEQUENCE public.userlog_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 %   DROP SEQUENCE public.userlog_id_seq;
       public       postgres    false    277    3            �           0    0    userlog_id_seq    SEQUENCE OWNED BY     A   ALTER SEQUENCE public.userlog_id_seq OWNED BY public.userlog.id;
            public       postgres    false    278            �           2604    17019    about_us id    DEFAULT     j   ALTER TABLE ONLY public.about_us ALTER COLUMN id SET DEFAULT nextval('public.about_us_id_seq'::regclass);
 :   ALTER TABLE public.about_us ALTER COLUMN id DROP DEFAULT;
       public       postgres    false    197    196            �           2604    17020    actionreg id    DEFAULT     l   ALTER TABLE ONLY public.actionreg ALTER COLUMN id SET DEFAULT nextval('public.actionreg_id_seq'::regclass);
 ;   ALTER TABLE public.actionreg ALTER COLUMN id DROP DEFAULT;
       public       postgres    false    199    198            �           2604    17021    additional_aq id    DEFAULT     t   ALTER TABLE ONLY public.additional_aq ALTER COLUMN id SET DEFAULT nextval('public.additional_aq_id_seq'::regclass);
 ?   ALTER TABLE public.additional_aq ALTER COLUMN id DROP DEFAULT;
       public       postgres    false    201    200            �           2604    17022 
   billing id    DEFAULT     h   ALTER TABLE ONLY public.billing ALTER COLUMN id SET DEFAULT nextval('public.billing_id_seq'::regclass);
 9   ALTER TABLE public.billing ALTER COLUMN id DROP DEFAULT;
       public       postgres    false    203    202            �           2604    17023 	   branch id    DEFAULT     f   ALTER TABLE ONLY public.branch ALTER COLUMN id SET DEFAULT nextval('public.branch_id_seq'::regclass);
 8   ALTER TABLE public.branch ALTER COLUMN id DROP DEFAULT;
       public       postgres    false    205    204            �           2604    17024    branch_professions id    DEFAULT     ~   ALTER TABLE ONLY public.branch_professions ALTER COLUMN id SET DEFAULT nextval('public.branch_professions_id_seq'::regclass);
 D   ALTER TABLE public.branch_professions ALTER COLUMN id DROP DEFAULT;
       public       postgres    false    207    206                        2604    17025    chat id    DEFAULT     b   ALTER TABLE ONLY public.chat ALTER COLUMN id SET DEFAULT nextval('public.chat_id_seq'::regclass);
 6   ALTER TABLE public.chat ALTER COLUMN id DROP DEFAULT;
       public       postgres    false    209    208                       2604    17026 
   company id    DEFAULT     h   ALTER TABLE ONLY public.company ALTER COLUMN id SET DEFAULT nextval('public.company_id_seq'::regclass);
 9   ALTER TABLE public.company ALTER COLUMN id DROP DEFAULT;
       public       postgres    false    213    210                       2604    17027    company_config id    DEFAULT     v   ALTER TABLE ONLY public.company_config ALTER COLUMN id SET DEFAULT nextval('public.company_config_id_seq'::regclass);
 @   ALTER TABLE public.company_config ALTER COLUMN id DROP DEFAULT;
       public       postgres    false    212    211                       2604    17028    company_info id    DEFAULT     r   ALTER TABLE ONLY public.company_info ALTER COLUMN id SET DEFAULT nextval('public.company_info_id_seq'::regclass);
 >   ALTER TABLE public.company_info ALTER COLUMN id DROP DEFAULT;
       public       postgres    false    215    214                       2604    17029    company_language id    DEFAULT     z   ALTER TABLE ONLY public.company_language ALTER COLUMN id SET DEFAULT nextval('public.company_language_id_seq'::regclass);
 B   ALTER TABLE public.company_language ALTER COLUMN id DROP DEFAULT;
       public       postgres    false    217    216                       2604    17030    company_profession id    DEFAULT     ~   ALTER TABLE ONLY public.company_profession ALTER COLUMN id SET DEFAULT nextval('public.company_profession_id_seq'::regclass);
 D   ALTER TABLE public.company_profession ALTER COLUMN id DROP DEFAULT;
       public       postgres    false    219    218                       2604    17031    company_settings id    DEFAULT     z   ALTER TABLE ONLY public.company_settings ALTER COLUMN id SET DEFAULT nextval('public.company_settings_id_seq'::regclass);
 B   ALTER TABLE public.company_settings ALTER COLUMN id DROP DEFAULT;
       public       postgres    false    223    222            	           2604    17032    company_skills id    DEFAULT     x   ALTER TABLE ONLY public.company_skills ALTER COLUMN id SET DEFAULT nextval('public.company_programm_id_seq'::regclass);
 @   ALTER TABLE public.company_skills ALTER COLUMN id DROP DEFAULT;
       public       postgres    false    221    220                       2604    17033    date_formats id    DEFAULT     r   ALTER TABLE ONLY public.date_formats ALTER COLUMN id SET DEFAULT nextval('public.date_formats_id_seq'::regclass);
 >   ALTER TABLE public.date_formats ALTER COLUMN id DROP DEFAULT;
       public       postgres    false    225    224                       2604    17034 
   friends id    DEFAULT     h   ALTER TABLE ONLY public.friends ALTER COLUMN id SET DEFAULT nextval('public.friends_id_seq'::regclass);
 9   ALTER TABLE public.friends ALTER COLUMN id DROP DEFAULT;
       public       postgres    false    227    226                       2604    17035 
   inforeg id    DEFAULT     h   ALTER TABLE ONLY public.inforeg ALTER COLUMN id SET DEFAULT nextval('public.inforeg_id_seq'::regclass);
 9   ALTER TABLE public.inforeg ALTER COLUMN id DROP DEFAULT;
       public       postgres    false    229    228                       2604    17036    language id    DEFAULT     j   ALTER TABLE ONLY public.language ALTER COLUMN id SET DEFAULT nextval('public.language_id_seq'::regclass);
 :   ALTER TABLE public.language ALTER COLUMN id DROP DEFAULT;
       public       postgres    false    231    230                       2604    17037    measure_step id    DEFAULT     r   ALTER TABLE ONLY public.measure_step ALTER COLUMN id SET DEFAULT nextval('public.measure_step_id_seq'::regclass);
 >   ALTER TABLE public.measure_step ALTER COLUMN id DROP DEFAULT;
       public       postgres    false    233    232                       2604    17038 
   meeting id    DEFAULT     h   ALTER TABLE ONLY public.meeting ALTER COLUMN id SET DEFAULT nextval('public.meeting_id_seq'::regclass);
 9   ALTER TABLE public.meeting ALTER COLUMN id DROP DEFAULT;
       public       postgres    false    235    234                       2604    17039    new_test id    DEFAULT     j   ALTER TABLE ONLY public.new_test ALTER COLUMN id SET DEFAULT nextval('public.new_test_id_seq'::regclass);
 :   ALTER TABLE public.new_test ALTER COLUMN id DROP DEFAULT;
       public       postgres    false    238    237                       2604    17040    notification id    DEFAULT     r   ALTER TABLE ONLY public.notification ALTER COLUMN id SET DEFAULT nextval('public.notification_id_seq'::regclass);
 >   ALTER TABLE public.notification ALTER COLUMN id DROP DEFAULT;
       public       postgres    false    240    239                       2604    17041 
   ourwork id    DEFAULT     h   ALTER TABLE ONLY public.ourwork ALTER COLUMN id SET DEFAULT nextval('public.ourwork_id_seq'::regclass);
 9   ALTER TABLE public.ourwork ALTER COLUMN id DROP DEFAULT;
       public       postgres    false    242    241                       2604    17042    profession id    DEFAULT     n   ALTER TABLE ONLY public.profession ALTER COLUMN id SET DEFAULT nextval('public.profession_id_seq'::regclass);
 <   ALTER TABLE public.profession ALTER COLUMN id DROP DEFAULT;
       public       postgres    false    244    243                       2604    17043    profession_skills id    DEFAULT     |   ALTER TABLE ONLY public.profession_skills ALTER COLUMN id SET DEFAULT nextval('public.profession_skills_id_seq'::regclass);
 C   ALTER TABLE public.profession_skills ALTER COLUMN id DROP DEFAULT;
       public       postgres    false    246    245                        2604    17044 
   profile id    DEFAULT     h   ALTER TABLE ONLY public.profile ALTER COLUMN id SET DEFAULT nextval('public.profile_id_seq'::regclass);
 9   ALTER TABLE public.profile ALTER COLUMN id DROP DEFAULT;
       public       postgres    false    248    247            #           2604    17045    reference id    DEFAULT     l   ALTER TABLE ONLY public.reference ALTER COLUMN id SET DEFAULT nextval('public.reference_id_seq'::regclass);
 ;   ALTER TABLE public.reference ALTER COLUMN id DROP DEFAULT;
       public       postgres    false    252    251            "           2604    17046 	   skills id    DEFAULT     h   ALTER TABLE ONLY public.skills ALTER COLUMN id SET DEFAULT nextval('public.programm_id_seq'::regclass);
 8   ALTER TABLE public.skills ALTER COLUMN id DROP DEFAULT;
       public       postgres    false    250    249            $           2604    17047    user id    DEFAULT     d   ALTER TABLE ONLY public."user" ALTER COLUMN id SET DEFAULT nextval('public.user_id_seq'::regclass);
 8   ALTER TABLE public."user" ALTER COLUMN id DROP DEFAULT;
       public       postgres    false    260    253            '           2604    17048    user_add id    DEFAULT     j   ALTER TABLE ONLY public.user_add ALTER COLUMN id SET DEFAULT nextval('public.user_add_id_seq'::regclass);
 :   ALTER TABLE public.user_add ALTER COLUMN id DROP DEFAULT;
       public       postgres    false    255    254            0           2604    17049    user_ask id    DEFAULT     j   ALTER TABLE ONLY public.user_ask ALTER COLUMN id SET DEFAULT nextval('public.user_ask_id_seq'::regclass);
 :   ALTER TABLE public.user_ask ALTER COLUMN id DROP DEFAULT;
       public       postgres    false    257    256            7           2604    17050    user_family id    DEFAULT     p   ALTER TABLE ONLY public.user_family ALTER COLUMN id SET DEFAULT nextval('public.user_family_id_seq'::regclass);
 =   ALTER TABLE public.user_family ALTER COLUMN id DROP DEFAULT;
       public       postgres    false    259    258            9           2604    17051    user_lang id    DEFAULT     l   ALTER TABLE ONLY public.user_lang ALTER COLUMN id SET DEFAULT nextval('public.user_lang_id_seq'::regclass);
 ;   ALTER TABLE public.user_lang ALTER COLUMN id DROP DEFAULT;
       public       postgres    false    262    261            <           2604    17052    user_pdf id    DEFAULT     j   ALTER TABLE ONLY public.user_pdf ALTER COLUMN id SET DEFAULT nextval('public.user_pdf_id_seq'::regclass);
 :   ALTER TABLE public.user_pdf ALTER COLUMN id DROP DEFAULT;
       public       postgres    false    264    263            =           2604    17053    user_skill id    DEFAULT     m   ALTER TABLE ONLY public.user_skill ALTER COLUMN id SET DEFAULT nextval('public.user_prog_id_seq'::regclass);
 <   ALTER TABLE public.user_skill ALTER COLUMN id DROP DEFAULT;
       public       postgres    false    266    265            >           2604    17054    user_study id    DEFAULT     n   ALTER TABLE ONLY public.user_study ALTER COLUMN id SET DEFAULT nextval('public.user_study_id_seq'::regclass);
 <   ALTER TABLE public.user_study ALTER COLUMN id DROP DEFAULT;
       public       postgres    false    268    267            E           2604    17055    user_trip id    DEFAULT     l   ALTER TABLE ONLY public.user_trip ALTER COLUMN id SET DEFAULT nextval('public.user_trip_id_seq'::regclass);
 ;   ALTER TABLE public.user_trip ALTER COLUMN id DROP DEFAULT;
       public       postgres    false    270    269            I           2604    17056    user_work id    DEFAULT     l   ALTER TABLE ONLY public.user_work ALTER COLUMN id SET DEFAULT nextval('public.user_work_id_seq'::regclass);
 ;   ALTER TABLE public.user_work ALTER COLUMN id DROP DEFAULT;
       public       postgres    false    272    271            S           2604    17057    userinfo id    DEFAULT     j   ALTER TABLE ONLY public.userinfo ALTER COLUMN id SET DEFAULT nextval('public.userinfo_id_seq'::regclass);
 :   ALTER TABLE public.userinfo ALTER COLUMN id DROP DEFAULT;
       public       postgres    false    274    273            U           2604    17058    userinfo_last id    DEFAULT     t   ALTER TABLE ONLY public.userinfo_last ALTER COLUMN id SET DEFAULT nextval('public.userinfo_last_id_seq'::regclass);
 ?   ALTER TABLE public.userinfo_last ALTER COLUMN id DROP DEFAULT;
       public       postgres    false    276    275            W           2604    17059 
   userlog id    DEFAULT     h   ALTER TABLE ONLY public.userlog ALTER COLUMN id SET DEFAULT nextval('public.userlog_id_seq'::regclass);
 9   ALTER TABLE public.userlog ALTER COLUMN id DROP DEFAULT;
       public       postgres    false    278    277            ^          0    16695    about_us 
   TABLE DATA               Y   COPY public.about_us (id, company_id, text, file, type, tg_file_id, text_ru) FROM stdin;
    public       postgres    false    196   D�      `          0    16703 	   actionreg 
   TABLE DATA               W   COPY public.actionreg (id, company_id, chat_id, step_1, step_2, order_num) FROM stdin;
    public       postgres    false    198   a�      b          0    16708    additional_aq 
   TABLE DATA               Q   COPY public.additional_aq (id, company_id, user_id, step_id, answer) FROM stdin;
    public       postgres    false    200   ��      d          0    16716    billing 
   TABLE DATA               h   COPY public.billing (id, company_id, start_date, end_date, billine_date, type, status, sum) FROM stdin;
    public       postgres    false    202   #�      f          0    16721    branch 
   TABLE DATA               =   COPY public.branch (id, title, status, title_ru) FROM stdin;
    public       postgres    false    204   @�      h          0    16729    branch_professions 
   TABLE DATA               J   COPY public.branch_professions (id, branch_id, profession_id) FROM stdin;
    public       postgres    false    206   ]�      j          0    16734    chat 
   TABLE DATA               P   COPY public.chat (id, type, message, datetime, company_id, chat_id) FROM stdin;
    public       postgres    false    208   z�      l          0    16742    company 
   TABLE DATA               r   COPY public.company (id, title, unique_title, bot_token, generate_key, send_id, is_active, branch_id) FROM stdin;
    public       postgres    false    210   ��      m          0    16749    company_config 
   TABLE DATA               D   COPY public.company_config (id, company_id, pdf_status) FROM stdin;
    public       postgres    false    211   
�      p          0    16756    company_info 
   TABLE DATA               d   COPY public.company_info (id, company_id, file_id, description, type, is_begin, status) FROM stdin;
    public       postgres    false    214   '�      r          0    16766    company_language 
   TABLE DATA               O   COPY public.company_language (id, company_id, language_id, status) FROM stdin;
    public       postgres    false    216   ��      t          0    16771    company_profession 
   TABLE DATA               S   COPY public.company_profession (id, company_id, profession_id, status) FROM stdin;
    public       postgres    false    218   �      x          0    16781    company_settings 
   TABLE DATA               h   COPY public.company_settings (id, company_id, step, title_uz, title_ru, order_step, status) FROM stdin;
    public       postgres    false    222   =�      v          0    16776    company_skills 
   TABLE DATA               Y   COPY public.company_skills (id, company_id, skill_id, status, profession_id) FROM stdin;
    public       postgres    false    220   H�      z          0    16791    date_formats 
   TABLE DATA               F   COPY public.date_formats (id, name, status, example_view) FROM stdin;
    public       postgres    false    224   y�      |          0    16799    friends 
   TABLE DATA               J   COPY public.friends (id, company_id, chat_id, friend_chat_id) FROM stdin;
    public       postgres    false    226   ��      ~          0    16804    inforeg 
   TABLE DATA               K   COPY public.inforeg (id, company_id, user_id, chat_id, status) FROM stdin;
    public       postgres    false    228   ��      �          0    16809    language 
   TABLE DATA               B   COPY public.language (id, title_uz, title_ru, status) FROM stdin;
    public       postgres    false    230   �      �          0    16819    measure_step 
   TABLE DATA               F   COPY public.measure_step (id, title_uz, title_ru, status) FROM stdin;
    public       postgres    false    232   P�      �          0    16827    meeting 
   TABLE DATA               `   COPY public.meeting (id, company_id, datetime, chat_id, description, reminder_time) FROM stdin;
    public       postgres    false    234   m�      �          0    16835 	   migration 
   TABLE DATA               8   COPY public.migration (version, apply_time) FROM stdin;
    public       postgres    false    236   ��      �          0    16838    new_test 
   TABLE DATA               :   COPY public.new_test (id, name, date, "time") FROM stdin;
    public       postgres    false    237   ��      �          0    16843    notification 
   TABLE DATA               m   COPY public.notification (id, company_id, datetime, message, file_id, type_col, is_referal_link) FROM stdin;
    public       postgres    false    239   ĺ      �          0    16851    ourwork 
   TABLE DATA               r   COPY public.ourwork (id, company_id, user_id, about_us, human_name, human_work, human_phone, chat_id) FROM stdin;
    public       postgres    false    241   �      �          0    16862 
   profession 
   TABLE DATA               D   COPY public.profession (id, title_uz, title_ru, status) FROM stdin;
    public       postgres    false    243   ��      �          0    16872    profession_skills 
   TABLE DATA               H   COPY public.profession_skills (id, profession_id, skill_id) FROM stdin;
    public       postgres    false    245   I�      �          0    16877    profile 
   TABLE DATA               }   COPY public.profile (id, firstname, lastname, email, company_id, password_hash, created_at, updated_at, user_id) FROM stdin;
    public       postgres    false    247   ��      �          0    16894 	   reference 
   TABLE DATA               �   COPY public.reference (id, company_id, title, date, description, oxvat, type, file, status, title_ru, description_ru) FROM stdin;
    public       postgres    false    251   ��      �          0    16885    skills 
   TABLE DATA               L   COPY public.skills (id, profession_id, title, status, title_ru) FROM stdin;
    public       postgres    false    249   ��      �          0    16902    user 
   TABLE DATA               �   COPY public."user" (id, username, email, password_hash, last_visited, status, auth_key, password_reset_token, verification_token, created_at, date_format_id) FROM stdin;
    public       postgres    false    253   N�      �          0    16908    user_add 
   TABLE DATA               v   COPY public.user_add (id, company_id, user_id, work_trip, user_army, user_court, car, car_grade, chat_id) FROM stdin;
    public       postgres    false    254   k�      �          0    16915    user_ask 
   TABLE DATA               �   COPY public.user_ask (id, company_id, user_id, new_salary, work_year, after_work, meet_work, collectiv_work, meet_parent, healthy, chat_id) FROM stdin;
    public       postgres    false    256   ��      �          0    16930    user_family 
   TABLE DATA               �   COPY public.user_family (id, company_id, user_id, family_member, member_name, member_birth, member_work, member_phone, member_live, member_court, chat_id) FROM stdin;
    public       postgres    false    258   ��      �          0    16945 	   user_lang 
   TABLE DATA               r   COPY public.user_lang (id, company_id, user_id, language, user_speak, user_write, user_read, chat_id) FROM stdin;
    public       postgres    false    261   Z�      �          0    16950    user_pdf 
   TABLE DATA               N   COPY public.user_pdf (id, company_id, user_id, chat_id, doc_name) FROM stdin;
    public       postgres    false    263   ��      �          0    16956 
   user_skill 
   TABLE DATA               G   COPY public.user_skill (id, company_id, user_id, skill_id) FROM stdin;
    public       postgres    false    265   ��      �          0    16961 
   user_study 
   TABLE DATA               x   COPY public.user_study (id, company_id, user_id, study_name, study_year, study_field, study_grade, chat_id) FROM stdin;
    public       postgres    false    267   "�      �          0    16969 	   user_trip 
   TABLE DATA               v   COPY public.user_trip (id, company_id, user_id, trip_status, trip_place, trip_year, trip_reason, chat_id) FROM stdin;
    public       postgres    false    269   V
      �          0    16979 	   user_work 
   TABLE DATA               v   COPY public.user_work (id, company_id, user_id, work, work_place, work_year, work_pos, work_out, chat_id) FROM stdin;
    public       postgres    false    271         �          0    16989    userinfo 
   TABLE DATA                 COPY public.userinfo (id, company_id, profession_id, chat_id, username, firstname, fullname, birthday, nationality, birth_place, live_place, live_status, phone, mail, marry, is_active, app_condition, date_application, is_archive, reason_archive, archive_date) FROM stdin;
    public       postgres    false    273   ;!      �          0    17005    userinfo_last 
   TABLE DATA               ]   COPY public.userinfo_last (id, company_id, user_id, goods, bads, image, chat_id) FROM stdin;
    public       postgres    false    275   f>      �          0    17014    userlog 
   TABLE DATA               I   COPY public.userlog (id, company_id, last_id, chat_id, type) FROM stdin;
    public       postgres    false    277   nK      �           0    0    about_us_id_seq    SEQUENCE SET     >   SELECT pg_catalog.setval('public.about_us_id_seq', 1, false);
            public       postgres    false    197            �           0    0    actionreg_id_seq    SEQUENCE SET     @   SELECT pg_catalog.setval('public.actionreg_id_seq', 101, true);
            public       postgres    false    199            �           0    0    additional_aq_id_seq    SEQUENCE SET     C   SELECT pg_catalog.setval('public.additional_aq_id_seq', 19, true);
            public       postgres    false    201            �           0    0    billing_id_seq    SEQUENCE SET     =   SELECT pg_catalog.setval('public.billing_id_seq', 1, false);
            public       postgres    false    203            �           0    0    branch_id_seq    SEQUENCE SET     <   SELECT pg_catalog.setval('public.branch_id_seq', 1, false);
            public       postgres    false    205            �           0    0    branch_professions_id_seq    SEQUENCE SET     H   SELECT pg_catalog.setval('public.branch_professions_id_seq', 1, false);
            public       postgres    false    207            �           0    0    chat_id_seq    SEQUENCE SET     :   SELECT pg_catalog.setval('public.chat_id_seq', 1, false);
            public       postgres    false    209            �           0    0    company_config_id_seq    SEQUENCE SET     D   SELECT pg_catalog.setval('public.company_config_id_seq', 1, false);
            public       postgres    false    212            �           0    0    company_id_seq    SEQUENCE SET     =   SELECT pg_catalog.setval('public.company_id_seq', 1, false);
            public       postgres    false    213            �           0    0    company_info_id_seq    SEQUENCE SET     B   SELECT pg_catalog.setval('public.company_info_id_seq', 1, false);
            public       postgres    false    215            �           0    0    company_language_id_seq    SEQUENCE SET     F   SELECT pg_catalog.setval('public.company_language_id_seq', 1, false);
            public       postgres    false    217            �           0    0    company_profession_id_seq    SEQUENCE SET     H   SELECT pg_catalog.setval('public.company_profession_id_seq', 1, false);
            public       postgres    false    219            �           0    0    company_programm_id_seq    SEQUENCE SET     E   SELECT pg_catalog.setval('public.company_programm_id_seq', 4, true);
            public       postgres    false    221            �           0    0    company_settings_id_seq    SEQUENCE SET     G   SELECT pg_catalog.setval('public.company_settings_id_seq', 199, true);
            public       postgres    false    223            �           0    0    date_formats_id_seq    SEQUENCE SET     B   SELECT pg_catalog.setval('public.date_formats_id_seq', 1, false);
            public       postgres    false    225            �           0    0    friends_id_seq    SEQUENCE SET     =   SELECT pg_catalog.setval('public.friends_id_seq', 1, false);
            public       postgres    false    227            �           0    0    inforeg_id_seq    SEQUENCE SET     >   SELECT pg_catalog.setval('public.inforeg_id_seq', 188, true);
            public       postgres    false    229            �           0    0    language_id_seq    SEQUENCE SET     >   SELECT pg_catalog.setval('public.language_id_seq', 1, false);
            public       postgres    false    231            �           0    0    measure_step_id_seq    SEQUENCE SET     B   SELECT pg_catalog.setval('public.measure_step_id_seq', 1, false);
            public       postgres    false    233            �           0    0    meeting_id_seq    SEQUENCE SET     =   SELECT pg_catalog.setval('public.meeting_id_seq', 1, false);
            public       postgres    false    235            �           0    0    new_test_id_seq    SEQUENCE SET     >   SELECT pg_catalog.setval('public.new_test_id_seq', 1, false);
            public       postgres    false    238            �           0    0    notification_id_seq    SEQUENCE SET     B   SELECT pg_catalog.setval('public.notification_id_seq', 1, false);
            public       postgres    false    240            �           0    0    ourwork_id_seq    SEQUENCE SET     =   SELECT pg_catalog.setval('public.ourwork_id_seq', 77, true);
            public       postgres    false    242            �           0    0    profession_id_seq    SEQUENCE SET     ?   SELECT pg_catalog.setval('public.profession_id_seq', 2, true);
            public       postgres    false    244            �           0    0    profession_skills_id_seq    SEQUENCE SET     G   SELECT pg_catalog.setval('public.profession_skills_id_seq', 1, false);
            public       postgres    false    246            �           0    0    profile_id_seq    SEQUENCE SET     =   SELECT pg_catalog.setval('public.profile_id_seq', 1, false);
            public       postgres    false    248            �           0    0    programm_id_seq    SEQUENCE SET     =   SELECT pg_catalog.setval('public.programm_id_seq', 1, true);
            public       postgres    false    250            �           0    0    reference_id_seq    SEQUENCE SET     ?   SELECT pg_catalog.setval('public.reference_id_seq', 1, false);
            public       postgres    false    252            �           0    0    user_add_id_seq    SEQUENCE SET     >   SELECT pg_catalog.setval('public.user_add_id_seq', 92, true);
            public       postgres    false    255            �           0    0    user_ask_id_seq    SEQUENCE SET     >   SELECT pg_catalog.setval('public.user_ask_id_seq', 71, true);
            public       postgres    false    257                        0    0    user_family_id_seq    SEQUENCE SET     B   SELECT pg_catalog.setval('public.user_family_id_seq', 122, true);
            public       postgres    false    259                       0    0    user_id_seq    SEQUENCE SET     :   SELECT pg_catalog.setval('public.user_id_seq', 1, false);
            public       postgres    false    260                       0    0    user_lang_id_seq    SEQUENCE SET     ?   SELECT pg_catalog.setval('public.user_lang_id_seq', 87, true);
            public       postgres    false    262                       0    0    user_pdf_id_seq    SEQUENCE SET     >   SELECT pg_catalog.setval('public.user_pdf_id_seq', 60, true);
            public       postgres    false    264                       0    0    user_prog_id_seq    SEQUENCE SET     ?   SELECT pg_catalog.setval('public.user_prog_id_seq', 97, true);
            public       postgres    false    266                       0    0    user_study_id_seq    SEQUENCE SET     A   SELECT pg_catalog.setval('public.user_study_id_seq', 149, true);
            public       postgres    false    268                       0    0    user_trip_id_seq    SEQUENCE SET     ?   SELECT pg_catalog.setval('public.user_trip_id_seq', 31, true);
            public       postgres    false    270                       0    0    user_work_id_seq    SEQUENCE SET     @   SELECT pg_catalog.setval('public.user_work_id_seq', 102, true);
            public       postgres    false    272                       0    0    userinfo_id_seq    SEQUENCE SET     ?   SELECT pg_catalog.setval('public.userinfo_id_seq', 190, true);
            public       postgres    false    274            	           0    0    userinfo_last_id_seq    SEQUENCE SET     C   SELECT pg_catalog.setval('public.userinfo_last_id_seq', 71, true);
            public       postgres    false    276            
           0    0    userlog_id_seq    SEQUENCE SET     ?   SELECT pg_catalog.setval('public.userlog_id_seq', 5946, true);
            public       postgres    false    278            Y           2606    17061    about_us about_us_pkey 
   CONSTRAINT     T   ALTER TABLE ONLY public.about_us
    ADD CONSTRAINT about_us_pkey PRIMARY KEY (id);
 @   ALTER TABLE ONLY public.about_us DROP CONSTRAINT about_us_pkey;
       public         postgres    false    196            [           2606    17063    actionreg actionreg_pkey 
   CONSTRAINT     V   ALTER TABLE ONLY public.actionreg
    ADD CONSTRAINT actionreg_pkey PRIMARY KEY (id);
 B   ALTER TABLE ONLY public.actionreg DROP CONSTRAINT actionreg_pkey;
       public         postgres    false    198            _           2606    17065     additional_aq additional_aq_pkey 
   CONSTRAINT     ^   ALTER TABLE ONLY public.additional_aq
    ADD CONSTRAINT additional_aq_pkey PRIMARY KEY (id);
 J   ALTER TABLE ONLY public.additional_aq DROP CONSTRAINT additional_aq_pkey;
       public         postgres    false    200            a           2606    17067    billing billing_pkey 
   CONSTRAINT     R   ALTER TABLE ONLY public.billing
    ADD CONSTRAINT billing_pkey PRIMARY KEY (id);
 >   ALTER TABLE ONLY public.billing DROP CONSTRAINT billing_pkey;
       public         postgres    false    202            d           2606    17069    branch branch_pkey 
   CONSTRAINT     P   ALTER TABLE ONLY public.branch
    ADD CONSTRAINT branch_pkey PRIMARY KEY (id);
 <   ALTER TABLE ONLY public.branch DROP CONSTRAINT branch_pkey;
       public         postgres    false    204            f           2606    17071 *   branch_professions branch_professions_pkey 
   CONSTRAINT     h   ALTER TABLE ONLY public.branch_professions
    ADD CONSTRAINT branch_professions_pkey PRIMARY KEY (id);
 T   ALTER TABLE ONLY public.branch_professions DROP CONSTRAINT branch_professions_pkey;
       public         postgres    false    206            h           2606    17073    chat chat_pkey 
   CONSTRAINT     L   ALTER TABLE ONLY public.chat
    ADD CONSTRAINT chat_pkey PRIMARY KEY (id);
 8   ALTER TABLE ONLY public.chat DROP CONSTRAINT chat_pkey;
       public         postgres    false    208            m           2606    17075 "   company_config company_config_pkey 
   CONSTRAINT     `   ALTER TABLE ONLY public.company_config
    ADD CONSTRAINT company_config_pkey PRIMARY KEY (id);
 L   ALTER TABLE ONLY public.company_config DROP CONSTRAINT company_config_pkey;
       public         postgres    false    211            o           2606    17077    company_info company_info_pkey 
   CONSTRAINT     \   ALTER TABLE ONLY public.company_info
    ADD CONSTRAINT company_info_pkey PRIMARY KEY (id);
 H   ALTER TABLE ONLY public.company_info DROP CONSTRAINT company_info_pkey;
       public         postgres    false    214            r           2606    17079 &   company_language company_language_pkey 
   CONSTRAINT     d   ALTER TABLE ONLY public.company_language
    ADD CONSTRAINT company_language_pkey PRIMARY KEY (id);
 P   ALTER TABLE ONLY public.company_language DROP CONSTRAINT company_language_pkey;
       public         postgres    false    216            k           2606    17081    company company_pkey 
   CONSTRAINT     R   ALTER TABLE ONLY public.company
    ADD CONSTRAINT company_pkey PRIMARY KEY (id);
 >   ALTER TABLE ONLY public.company DROP CONSTRAINT company_pkey;
       public         postgres    false    210            v           2606    17083 *   company_profession company_profession_pkey 
   CONSTRAINT     h   ALTER TABLE ONLY public.company_profession
    ADD CONSTRAINT company_profession_pkey PRIMARY KEY (id);
 T   ALTER TABLE ONLY public.company_profession DROP CONSTRAINT company_profession_pkey;
       public         postgres    false    218            y           2606    17085 $   company_skills company_programm_pkey 
   CONSTRAINT     b   ALTER TABLE ONLY public.company_skills
    ADD CONSTRAINT company_programm_pkey PRIMARY KEY (id);
 N   ALTER TABLE ONLY public.company_skills DROP CONSTRAINT company_programm_pkey;
       public         postgres    false    220            |           2606    17087 &   company_settings company_settings_pkey 
   CONSTRAINT     d   ALTER TABLE ONLY public.company_settings
    ADD CONSTRAINT company_settings_pkey PRIMARY KEY (id);
 P   ALTER TABLE ONLY public.company_settings DROP CONSTRAINT company_settings_pkey;
       public         postgres    false    222                       2606    17089    date_formats date_formats_pkey 
   CONSTRAINT     \   ALTER TABLE ONLY public.date_formats
    ADD CONSTRAINT date_formats_pkey PRIMARY KEY (id);
 H   ALTER TABLE ONLY public.date_formats DROP CONSTRAINT date_formats_pkey;
       public         postgres    false    224            �           2606    17091    friends friends_pkey 
   CONSTRAINT     R   ALTER TABLE ONLY public.friends
    ADD CONSTRAINT friends_pkey PRIMARY KEY (id);
 >   ALTER TABLE ONLY public.friends DROP CONSTRAINT friends_pkey;
       public         postgres    false    226            �           2606    17093    inforeg inforeg_pkey 
   CONSTRAINT     R   ALTER TABLE ONLY public.inforeg
    ADD CONSTRAINT inforeg_pkey PRIMARY KEY (id);
 >   ALTER TABLE ONLY public.inforeg DROP CONSTRAINT inforeg_pkey;
       public         postgres    false    228            �           2606    17095    language language_pkey 
   CONSTRAINT     T   ALTER TABLE ONLY public.language
    ADD CONSTRAINT language_pkey PRIMARY KEY (id);
 @   ALTER TABLE ONLY public.language DROP CONSTRAINT language_pkey;
       public         postgres    false    230            �           2606    17097    measure_step measure_step_pkey 
   CONSTRAINT     \   ALTER TABLE ONLY public.measure_step
    ADD CONSTRAINT measure_step_pkey PRIMARY KEY (id);
 H   ALTER TABLE ONLY public.measure_step DROP CONSTRAINT measure_step_pkey;
       public         postgres    false    232            �           2606    17099    meeting meeting_pkey 
   CONSTRAINT     R   ALTER TABLE ONLY public.meeting
    ADD CONSTRAINT meeting_pkey PRIMARY KEY (id);
 >   ALTER TABLE ONLY public.meeting DROP CONSTRAINT meeting_pkey;
       public         postgres    false    234            �           2606    17101    migration migration_pkey 
   CONSTRAINT     [   ALTER TABLE ONLY public.migration
    ADD CONSTRAINT migration_pkey PRIMARY KEY (version);
 B   ALTER TABLE ONLY public.migration DROP CONSTRAINT migration_pkey;
       public         postgres    false    236            �           2606    17103    new_test new_test_pkey 
   CONSTRAINT     T   ALTER TABLE ONLY public.new_test
    ADD CONSTRAINT new_test_pkey PRIMARY KEY (id);
 @   ALTER TABLE ONLY public.new_test DROP CONSTRAINT new_test_pkey;
       public         postgres    false    237            �           2606    17105    notification notification_pkey 
   CONSTRAINT     \   ALTER TABLE ONLY public.notification
    ADD CONSTRAINT notification_pkey PRIMARY KEY (id);
 H   ALTER TABLE ONLY public.notification DROP CONSTRAINT notification_pkey;
       public         postgres    false    239            �           2606    17107    ourwork ourwork_pkey 
   CONSTRAINT     R   ALTER TABLE ONLY public.ourwork
    ADD CONSTRAINT ourwork_pkey PRIMARY KEY (id);
 >   ALTER TABLE ONLY public.ourwork DROP CONSTRAINT ourwork_pkey;
       public         postgres    false    241            �           2606    17109    profession profession_pkey 
   CONSTRAINT     X   ALTER TABLE ONLY public.profession
    ADD CONSTRAINT profession_pkey PRIMARY KEY (id);
 D   ALTER TABLE ONLY public.profession DROP CONSTRAINT profession_pkey;
       public         postgres    false    243            �           2606    17111 (   profession_skills profession_skills_pkey 
   CONSTRAINT     f   ALTER TABLE ONLY public.profession_skills
    ADD CONSTRAINT profession_skills_pkey PRIMARY KEY (id);
 R   ALTER TABLE ONLY public.profession_skills DROP CONSTRAINT profession_skills_pkey;
       public         postgres    false    245            �           2606    17113    profile profile_pkey 
   CONSTRAINT     R   ALTER TABLE ONLY public.profile
    ADD CONSTRAINT profile_pkey PRIMARY KEY (id);
 >   ALTER TABLE ONLY public.profile DROP CONSTRAINT profile_pkey;
       public         postgres    false    247            �           2606    17115    skills programm_pkey 
   CONSTRAINT     R   ALTER TABLE ONLY public.skills
    ADD CONSTRAINT programm_pkey PRIMARY KEY (id);
 >   ALTER TABLE ONLY public.skills DROP CONSTRAINT programm_pkey;
       public         postgres    false    249            �           2606    17117    reference reference_pkey 
   CONSTRAINT     V   ALTER TABLE ONLY public.reference
    ADD CONSTRAINT reference_pkey PRIMARY KEY (id);
 B   ALTER TABLE ONLY public.reference DROP CONSTRAINT reference_pkey;
       public         postgres    false    251            �           2606    17119    user_add user_add_pkey 
   CONSTRAINT     T   ALTER TABLE ONLY public.user_add
    ADD CONSTRAINT user_add_pkey PRIMARY KEY (id);
 @   ALTER TABLE ONLY public.user_add DROP CONSTRAINT user_add_pkey;
       public         postgres    false    254            �           2606    17121    user_ask user_ask_pkey 
   CONSTRAINT     T   ALTER TABLE ONLY public.user_ask
    ADD CONSTRAINT user_ask_pkey PRIMARY KEY (id);
 @   ALTER TABLE ONLY public.user_ask DROP CONSTRAINT user_ask_pkey;
       public         postgres    false    256            �           2606    17123    user_family user_family_pkey 
   CONSTRAINT     Z   ALTER TABLE ONLY public.user_family
    ADD CONSTRAINT user_family_pkey PRIMARY KEY (id);
 F   ALTER TABLE ONLY public.user_family DROP CONSTRAINT user_family_pkey;
       public         postgres    false    258            �           2606    17125    user_lang user_lang_pkey 
   CONSTRAINT     V   ALTER TABLE ONLY public.user_lang
    ADD CONSTRAINT user_lang_pkey PRIMARY KEY (id);
 B   ALTER TABLE ONLY public.user_lang DROP CONSTRAINT user_lang_pkey;
       public         postgres    false    261            �           2606    17127    user_pdf user_pdf_pkey 
   CONSTRAINT     T   ALTER TABLE ONLY public.user_pdf
    ADD CONSTRAINT user_pdf_pkey PRIMARY KEY (id);
 @   ALTER TABLE ONLY public.user_pdf DROP CONSTRAINT user_pdf_pkey;
       public         postgres    false    263            �           2606    17129    user user_pkey 
   CONSTRAINT     N   ALTER TABLE ONLY public."user"
    ADD CONSTRAINT user_pkey PRIMARY KEY (id);
 :   ALTER TABLE ONLY public."user" DROP CONSTRAINT user_pkey;
       public         postgres    false    253            �           2606    17131    user_skill user_prog_pkey 
   CONSTRAINT     W   ALTER TABLE ONLY public.user_skill
    ADD CONSTRAINT user_prog_pkey PRIMARY KEY (id);
 C   ALTER TABLE ONLY public.user_skill DROP CONSTRAINT user_prog_pkey;
       public         postgres    false    265            �           2606    17133    user_study user_study_pkey 
   CONSTRAINT     X   ALTER TABLE ONLY public.user_study
    ADD CONSTRAINT user_study_pkey PRIMARY KEY (id);
 D   ALTER TABLE ONLY public.user_study DROP CONSTRAINT user_study_pkey;
       public         postgres    false    267            �           2606    17135    user_trip user_trip_pkey 
   CONSTRAINT     V   ALTER TABLE ONLY public.user_trip
    ADD CONSTRAINT user_trip_pkey PRIMARY KEY (id);
 B   ALTER TABLE ONLY public.user_trip DROP CONSTRAINT user_trip_pkey;
       public         postgres    false    269            �           2606    17137    user user_username_key 
   CONSTRAINT     W   ALTER TABLE ONLY public."user"
    ADD CONSTRAINT user_username_key UNIQUE (username);
 B   ALTER TABLE ONLY public."user" DROP CONSTRAINT user_username_key;
       public         postgres    false    253            �           2606    17139    user_work user_work_pkey 
   CONSTRAINT     V   ALTER TABLE ONLY public.user_work
    ADD CONSTRAINT user_work_pkey PRIMARY KEY (id);
 B   ALTER TABLE ONLY public.user_work DROP CONSTRAINT user_work_pkey;
       public         postgres    false    271            �           2606    17141     userinfo_last userinfo_last_pkey 
   CONSTRAINT     ^   ALTER TABLE ONLY public.userinfo_last
    ADD CONSTRAINT userinfo_last_pkey PRIMARY KEY (id);
 J   ALTER TABLE ONLY public.userinfo_last DROP CONSTRAINT userinfo_last_pkey;
       public         postgres    false    275            �           2606    17143    userinfo userinfo_pkey 
   CONSTRAINT     T   ALTER TABLE ONLY public.userinfo
    ADD CONSTRAINT userinfo_pkey PRIMARY KEY (id);
 @   ALTER TABLE ONLY public.userinfo DROP CONSTRAINT userinfo_pkey;
       public         postgres    false    273            �           2606    17145    userlog userlog_pkey 
   CONSTRAINT     R   ALTER TABLE ONLY public.userlog
    ADD CONSTRAINT userlog_pkey PRIMARY KEY (id);
 >   ALTER TABLE ONLY public.userlog DROP CONSTRAINT userlog_pkey;
       public         postgres    false    277            \           1259    17146    idx-actionreg-chat_id    INDEX     P   CREATE INDEX "idx-actionreg-chat_id" ON public.actionreg USING btree (chat_id);
 +   DROP INDEX public."idx-actionreg-chat_id";
       public         postgres    false    198            ]           1259    17147    idx-actionreg-company_id    INDEX     V   CREATE INDEX "idx-actionreg-company_id" ON public.actionreg USING btree (company_id);
 .   DROP INDEX public."idx-actionreg-company_id";
       public         postgres    false    198            b           1259    17148    idx-billing-company_id    INDEX     R   CREATE INDEX "idx-billing-company_id" ON public.billing USING btree (company_id);
 ,   DROP INDEX public."idx-billing-company_id";
       public         postgres    false    202            i           1259    17149    idx-chat-company_id    INDEX     L   CREATE INDEX "idx-chat-company_id" ON public.chat USING btree (company_id);
 )   DROP INDEX public."idx-chat-company_id";
       public         postgres    false    208            p           1259    17150    idx-company_info-company_id    INDEX     \   CREATE INDEX "idx-company_info-company_id" ON public.company_info USING btree (company_id);
 1   DROP INDEX public."idx-company_info-company_id";
       public         postgres    false    214            s           1259    17151    idx-company_language-company_id    INDEX     d   CREATE INDEX "idx-company_language-company_id" ON public.company_language USING btree (company_id);
 5   DROP INDEX public."idx-company_language-company_id";
       public         postgres    false    216            t           1259    17152     idx-company_language-language_id    INDEX     f   CREATE INDEX "idx-company_language-language_id" ON public.company_language USING btree (language_id);
 6   DROP INDEX public."idx-company_language-language_id";
       public         postgres    false    216            w           1259    17153 $   idx-company_profession-profession_id    INDEX     n   CREATE INDEX "idx-company_profession-profession_id" ON public.company_profession USING btree (profession_id);
 :   DROP INDEX public."idx-company_profession-profession_id";
       public         postgres    false    218            z           1259    17154     idx-company_programm-programm_id    INDEX     a   CREATE INDEX "idx-company_programm-programm_id" ON public.company_skills USING btree (skill_id);
 6   DROP INDEX public."idx-company_programm-programm_id";
       public         postgres    false    220            }           1259    17155    idx-company_settings-company_id    INDEX     d   CREATE INDEX "idx-company_settings-company_id" ON public.company_settings USING btree (company_id);
 5   DROP INDEX public."idx-company_settings-company_id";
       public         postgres    false    222            �           1259    17156    idx-friends-company_id    INDEX     R   CREATE INDEX "idx-friends-company_id" ON public.friends USING btree (company_id);
 ,   DROP INDEX public."idx-friends-company_id";
       public         postgres    false    226            �           1259    17157    idx-inforeg-chat_id    INDEX     L   CREATE INDEX "idx-inforeg-chat_id" ON public.inforeg USING btree (chat_id);
 )   DROP INDEX public."idx-inforeg-chat_id";
       public         postgres    false    228            �           1259    17158    idx-inforeg-company_id    INDEX     R   CREATE INDEX "idx-inforeg-company_id" ON public.inforeg USING btree (company_id);
 ,   DROP INDEX public."idx-inforeg-company_id";
       public         postgres    false    228            �           1259    17159    idx-inforeg-user_id    INDEX     L   CREATE INDEX "idx-inforeg-user_id" ON public.inforeg USING btree (user_id);
 )   DROP INDEX public."idx-inforeg-user_id";
       public         postgres    false    228            �           1259    17160    idx-meeting-company_id    INDEX     R   CREATE INDEX "idx-meeting-company_id" ON public.meeting USING btree (company_id);
 ,   DROP INDEX public."idx-meeting-company_id";
       public         postgres    false    234            �           1259    17161    idx-notification-company_id    INDEX     \   CREATE INDEX "idx-notification-company_id" ON public.notification USING btree (company_id);
 1   DROP INDEX public."idx-notification-company_id";
       public         postgres    false    239            �           1259    17162    idx-ourwork-company_id    INDEX     R   CREATE INDEX "idx-ourwork-company_id" ON public.ourwork USING btree (company_id);
 ,   DROP INDEX public."idx-ourwork-company_id";
       public         postgres    false    241            �           1259    17163    idx-ourwork-user_id    INDEX     L   CREATE INDEX "idx-ourwork-user_id" ON public.ourwork USING btree (user_id);
 )   DROP INDEX public."idx-ourwork-user_id";
       public         postgres    false    241            �           1259    17164    idx-programm-category_id    INDEX     V   CREATE INDEX "idx-programm-category_id" ON public.skills USING btree (profession_id);
 .   DROP INDEX public."idx-programm-category_id";
       public         postgres    false    249            �           1259    17165    idx-user_add-company_id    INDEX     T   CREATE INDEX "idx-user_add-company_id" ON public.user_add USING btree (company_id);
 -   DROP INDEX public."idx-user_add-company_id";
       public         postgres    false    254            �           1259    17166    idx-user_add-user_id    INDEX     N   CREATE INDEX "idx-user_add-user_id" ON public.user_add USING btree (user_id);
 *   DROP INDEX public."idx-user_add-user_id";
       public         postgres    false    254            �           1259    17167    idx-user_ask-company_id    INDEX     T   CREATE INDEX "idx-user_ask-company_id" ON public.user_ask USING btree (company_id);
 -   DROP INDEX public."idx-user_ask-company_id";
       public         postgres    false    256            �           1259    17168    idx-user_ask-user_id    INDEX     N   CREATE INDEX "idx-user_ask-user_id" ON public.user_ask USING btree (user_id);
 *   DROP INDEX public."idx-user_ask-user_id";
       public         postgres    false    256            �           1259    17169    idx-user_family-company_id    INDEX     Z   CREATE INDEX "idx-user_family-company_id" ON public.user_family USING btree (company_id);
 0   DROP INDEX public."idx-user_family-company_id";
       public         postgres    false    258            �           1259    17170    idx-user_family-user_id    INDEX     T   CREATE INDEX "idx-user_family-user_id" ON public.user_family USING btree (user_id);
 -   DROP INDEX public."idx-user_family-user_id";
       public         postgres    false    258            �           1259    17171    idx-user_lang-company_id    INDEX     V   CREATE INDEX "idx-user_lang-company_id" ON public.user_lang USING btree (company_id);
 .   DROP INDEX public."idx-user_lang-company_id";
       public         postgres    false    261            �           1259    17172    idx-user_lang-user_id    INDEX     P   CREATE INDEX "idx-user_lang-user_id" ON public.user_lang USING btree (user_id);
 +   DROP INDEX public."idx-user_lang-user_id";
       public         postgres    false    261            �           1259    17173    idx-user_pdf-chat_id    INDEX     N   CREATE INDEX "idx-user_pdf-chat_id" ON public.user_pdf USING btree (chat_id);
 *   DROP INDEX public."idx-user_pdf-chat_id";
       public         postgres    false    263            �           1259    17174    idx-user_pdf-company_id    INDEX     T   CREATE INDEX "idx-user_pdf-company_id" ON public.user_pdf USING btree (company_id);
 -   DROP INDEX public."idx-user_pdf-company_id";
       public         postgres    false    263            �           1259    17175    idx-user_pdf-user_id    INDEX     N   CREATE INDEX "idx-user_pdf-user_id" ON public.user_pdf USING btree (user_id);
 *   DROP INDEX public."idx-user_pdf-user_id";
       public         postgres    false    263            �           1259    17176    idx-user_prog-company_id    INDEX     W   CREATE INDEX "idx-user_prog-company_id" ON public.user_skill USING btree (company_id);
 .   DROP INDEX public."idx-user_prog-company_id";
       public         postgres    false    265            �           1259    17177    idx-user_study-company_id    INDEX     X   CREATE INDEX "idx-user_study-company_id" ON public.user_study USING btree (company_id);
 /   DROP INDEX public."idx-user_study-company_id";
       public         postgres    false    267            �           1259    17178    idx-user_study-user_id    INDEX     R   CREATE INDEX "idx-user_study-user_id" ON public.user_study USING btree (user_id);
 ,   DROP INDEX public."idx-user_study-user_id";
       public         postgres    false    267            �           1259    17179    idx-user_trip-company_id    INDEX     V   CREATE INDEX "idx-user_trip-company_id" ON public.user_trip USING btree (company_id);
 .   DROP INDEX public."idx-user_trip-company_id";
       public         postgres    false    269            �           1259    17180    idx-user_trip-user_id    INDEX     P   CREATE INDEX "idx-user_trip-user_id" ON public.user_trip USING btree (user_id);
 +   DROP INDEX public."idx-user_trip-user_id";
       public         postgres    false    269            �           1259    17181    idx-user_work-company_id    INDEX     V   CREATE INDEX "idx-user_work-company_id" ON public.user_work USING btree (company_id);
 .   DROP INDEX public."idx-user_work-company_id";
       public         postgres    false    271            �           1259    17182    idx-user_work-user_id    INDEX     P   CREATE INDEX "idx-user_work-user_id" ON public.user_work USING btree (user_id);
 +   DROP INDEX public."idx-user_work-user_id";
       public         postgres    false    271            �           1259    17183    idx-userinfo-chat_id    INDEX     N   CREATE INDEX "idx-userinfo-chat_id" ON public.userinfo USING btree (chat_id);
 *   DROP INDEX public."idx-userinfo-chat_id";
       public         postgres    false    273            �           1259    17184    idx-userinfo-company_id    INDEX     T   CREATE INDEX "idx-userinfo-company_id" ON public.userinfo USING btree (company_id);
 -   DROP INDEX public."idx-userinfo-company_id";
       public         postgres    false    273            �           1259    17185    idx-userinfo-profession_id    INDEX     Z   CREATE INDEX "idx-userinfo-profession_id" ON public.userinfo USING btree (profession_id);
 0   DROP INDEX public."idx-userinfo-profession_id";
       public         postgres    false    273            �           1259    17186    idx-userinfo_last-company_id    INDEX     ^   CREATE INDEX "idx-userinfo_last-company_id" ON public.userinfo_last USING btree (company_id);
 2   DROP INDEX public."idx-userinfo_last-company_id";
       public         postgres    false    275            �           1259    17187    idx-userinfo_last-user_id    INDEX     X   CREATE INDEX "idx-userinfo_last-user_id" ON public.userinfo_last USING btree (user_id);
 /   DROP INDEX public."idx-userinfo_last-user_id";
       public         postgres    false    275            �           1259    17188    idx-userlog-company_id    INDEX     R   CREATE INDEX "idx-userlog-company_id" ON public.userlog USING btree (company_id);
 ,   DROP INDEX public."idx-userlog-company_id";
       public         postgres    false    277            �           2620    17728    user_add user_add_last_id    TRIGGER     {   CREATE TRIGGER user_add_last_id BEFORE INSERT ON public.user_add FOR EACH ROW EXECUTE PROCEDURE public.user_add_last_id();
 2   DROP TRIGGER user_add_last_id ON public.user_add;
       public       postgres    false    254    286            �           2620    17792    user_ask user_ask_last_id    TRIGGER     {   CREATE TRIGGER user_ask_last_id BEFORE INSERT ON public.user_ask FOR EACH ROW EXECUTE PROCEDURE public.user_ask_last_id();
 2   DROP TRIGGER user_ask_last_id ON public.user_ask;
       public       postgres    false    282    256            �           2620    17849    user_family user_family_last_id    TRIGGER     �   CREATE TRIGGER user_family_last_id BEFORE INSERT ON public.user_family FOR EACH ROW EXECUTE PROCEDURE public.user_family_last_id();
 8   DROP TRIGGER user_family_last_id ON public.user_family;
       public       postgres    false    258    287            �           2620    18041    user_lang user_lang_last_id    TRIGGER     ~   CREATE TRIGGER user_lang_last_id BEFORE INSERT ON public.user_lang FOR EACH ROW EXECUTE PROCEDURE public.user_lang_last_id();
 4   DROP TRIGGER user_lang_last_id ON public.user_lang;
       public       postgres    false    300    261            �           2620    17809    userinfo_last user_last_last_id    TRIGGER     �   CREATE TRIGGER user_last_last_id BEFORE INSERT ON public.userinfo_last FOR EACH ROW EXECUTE PROCEDURE public.user_last_last_id();
 8   DROP TRIGGER user_last_last_id ON public.userinfo_last;
       public       postgres    false    275    285            �           2620    17760    ourwork user_our_last_id    TRIGGER     z   CREATE TRIGGER user_our_last_id BEFORE INSERT ON public.ourwork FOR EACH ROW EXECUTE PROCEDURE public.user_our_last_id();
 1   DROP TRIGGER user_our_last_id ON public.ourwork;
       public       postgres    false    241    280            �           2620    17642    user_study user_study_last_id    TRIGGER     �   CREATE TRIGGER user_study_last_id BEFORE INSERT ON public.user_study FOR EACH ROW EXECUTE PROCEDURE public.user_study_last_id();
 6   DROP TRIGGER user_study_last_id ON public.user_study;
       public       postgres    false    281    267            �           2620    17649    user_trip user_trip_last_id    TRIGGER     ~   CREATE TRIGGER user_trip_last_id BEFORE INSERT ON public.user_trip FOR EACH ROW EXECUTE PROCEDURE public.user_trip_last_id();
 4   DROP TRIGGER user_trip_last_id ON public.user_trip;
       public       postgres    false    269    284            �           2620    17647    user_work user_work_last_id    TRIGGER     ~   CREATE TRIGGER user_work_last_id BEFORE INSERT ON public.user_work FOR EACH ROW EXECUTE PROCEDURE public.user_work_last_id();
 4   DROP TRIGGER user_work_last_id ON public.user_work;
       public       postgres    false    271    283            �           2620    17539    userinfo userinforeg_trigger    TRIGGER     y   CREATE TRIGGER userinforeg_trigger BEFORE INSERT ON public.userinfo FOR EACH ROW EXECUTE PROCEDURE public.userinforeg();
 5   DROP TRIGGER userinforeg_trigger ON public.userinfo;
       public       postgres    false    273    279            �           2606    17189 *   company_config company_config_with_company    FK CONSTRAINT     �   ALTER TABLE ONLY public.company_config
    ADD CONSTRAINT company_config_with_company FOREIGN KEY (company_id) REFERENCES public.company(id) ON DELETE CASCADE;
 T   ALTER TABLE ONLY public.company_config DROP CONSTRAINT company_config_with_company;
       public       postgres    false    211    3179    210            �           2606    17194    about_us company_with_about_us    FK CONSTRAINT     �   ALTER TABLE ONLY public.about_us
    ADD CONSTRAINT company_with_about_us FOREIGN KEY (company_id) REFERENCES public.company(id) ON DELETE CASCADE;
 H   ALTER TABLE ONLY public.about_us DROP CONSTRAINT company_with_about_us;
       public       postgres    false    3179    196    210            ^      x������ � �      `   �  x�MUۑ�6�^����K��# e9w��٥%
A���?�.)u��=�Ţ��4���y�f��	e��G�ꉉItQۛ���A�X�ΖV��\?$��/�m:�����zō�.�t�f�7Z�6���Hޣb�R�BV�[�]�5䮜���3�kR�O��T$���->���QvҨ#���99���2�i�cR{���yb2U;�D��u��l��3{yPRx��U�0���(%m��i�B���#��p�۩�ŜƁ��R%��i`@*� "��mE����.�/ ���B��Qhgڕ�/�"e��*4�=߾�T3�r����U(z����DM7g���G�Ȫ�+�?���T�:n�|
t�����/�:���e�lr*��(.��J�
E�{2��c��A�GP�Z��ۖi����~�Y4UnT�j�����\$�ܬ��H��~A[)�3�B�:=4�����qI�=}��4������Ab$���Q�6�ڟ���FfLO\��c!{߲̒rM�G��Q�����%Pwذ�q�pc�H�Ƶ�u4���Aޟ4}���V�?�͆@�h읚=	"��Z�_?��� �d�3J��.݉�w�����ï��8;SN^&���!��b��w/��2R���� ���Vz�ȋ��]�_w�	�?�2������6�g���*�o��b��6���8���f����j����M�/�"���)m�*C��>�r�'{�|s�Z��ѻ���^�0�c���M�����1�[���c���4H�FW+�K9��5Z5�O`vy����ش��?j�Ep۸9�1̓AH�}$,�(�3��oi���1;�C���Y(b�x�]�.[\�_�VH��1b���!�"6�k�=��y?�y�����z|7���<���SX      b     x�U�]O�0���c���6-�Ȓ9���T�0���m�I�M�>�=}(#�� �G����t�kzz�.�G:i�P�#��z�Z�CU�	���� ]�]�^yU)��!jk�5<$�c�':�Q`�.�yvꌊ�R��3y�jj5��J��j�z7�f�*ou�9�L�h���L1d����6"D!q_39���TC�xv���i���v�y����x�Q�HC��f�a�=@��1�2��c�ş�dW�pa!��2�k�MY��y�g�����?Ϸ;J�/��p      d      x������ � �      f      x������ � �      h      x������ � �      j      x������ � �      l   c   x�3��,I��K-�цf��&fƆ��&�Ʀ��&�V��n.>i�%�9e�F9Y)���ž���iI�Ea��f��F�&F�1~�@s�b���� �K�      m      x������ � �      p   �   x�M����0���)� G��ǂXX`d�sT��$V��<����ߟ�۽9�����`�T��j�@�3�.F�}�๐k� ��57j�e�#L���1Q{"�����@*�@�**�+�5�u0�'�I�bM\".��]=/ڲpRL>���}L���B�d�|�P؍*n7
�6��1��Ӹ�֔a-�7}��]��� `K      r      x������ � �      t      x�3�4�4�4����� ��      x   �
  x��YKo�f]3���p��(y%�S�� �@��mR������I� ���`03E�U+�_�_ �ќs?�$+��8h�Hy��{��|1o𿿩��c��u�����f�e6�[[�g��_�M�����M���V���C��'f0	�G�(83����_ȕ1���p?���Oq'.�o��\C׆���Wi|�¿��g�9���VL����6U��pZ��n�1����×���(R�/��U�o��!�<g����p��A2u��`<�o�;�j�]�R��h\�\S��$N_�&�.��"d���������}�C����p����	?g��;����A%� ���z��Y� QQJ0�t�܏�r���谡�6~���1w�m.�(�n��n���/��Ԍ�	�(��tR{���ϑZf�w� �T�U:5�P3��X�XP�|O�uL|]F.�� v�,j*PS15�Ɔ�2;��-�V������SS����u�r�J�k��f�o�����SWf�����ن/[��1W�;(�ߥ���S�R�������CP8�	'#�fi�q�+>�C/Wa�*���V���H�j|���NgF��$��%M�(��Q��J%o<R�%u�Q[jYM'��S�$Ćo����c��d3/�Q"c�-��z�aY�������w�|��:޸ّd1D�
_�e�}?|��J^X�D��L-hj!II���eT�I�W$��B���)�3���5���B5%RM��௷�뀨�ـ�f،.>g�	��
�H8���ݦ�Z�l��F�����,J�-��ڕH_��lÄ�P�`���M�Gӛ�����`�?�zǑ�/�С��4uW��(|�V�]��@/&��(�B
,�#x��Y�'5�jkɗ> Z�
N�@<���o��|������:�U��r�r��Vf���~牏���=�ֶ��=T;t��E��q���J}�8{�����j$�$M�O�l.#d��ܢ�G�u�-�G�e��ȈϘy󺸙93p�%.�ѐ��h.-'5�+����uD�q�; �� ��R>�9�����t�#M8=*%����>��$�S �5Q���7�Ƒ#G%�g�r�U�C�l7�+��U5Us��ޗ������;��x�;@R��ЯM����+7��W*ݧ�H���y:
,.�ϖ�L}K�NU8+ؒ�`�<ij6z&��� ��
�v;-�����&��
���Ё�%�\
��q�)�=fG������ kI�����P�`���������\�M\e��!5	�rT@]lV1ֵɄ?nb���\때�qˁ��f���J���"�RFo��F6���������!f���bpL�%�m��-{� 6� ��	�*ep]Ҿ�$n��Y�#l��,à��w�4G��lNђ�����{h!޽:_��+	�M�����)t���I4B�������{p��>��g���-�6��U1~B܉"sc�^(�:p��3+�J�J_������p��%�BK�@�}�����ި
�A�Rº�p�eN!�2ɾ���gJO����{���@�&{�م�S�ݨ1_�cH���pu-d����@˲��@�u.�j���:�d��8��A���iRb��� �*�u�������f8u��c�`q憿��D$[-i��%��'��G���d�B��k4&�|�o9��'ή��j���:2-����$󌋪ޘ24Hvf���#$SM��5Mϖ�J�hɼ`Q�@�m�	X�7g8A�JCE�3ѫ��h��]� <X&��O��2��0c�聆��#.uo{zHu>�8��J�F>+T���q�qͦک��6����\�	|�kGԷt�s����`�v�=J���R�cX�&*%�֌��h���N��_0���
?={R>KY��@J�
�UHu��X[�����2kQدB�+��Q�u�H{˭��c�>�:��s3q�zR:������_�%���o�����gB�_�`���ޖ�K}����Q4��y�>Dع5�Ke�PL�S̞%��%,�̶��\����7sr�TL<r�o���x����]�lW�3�F�����gS2��?I'�I����<p����n�'���Ea�
��
ve���MZ�6���u���=��.����Q�ܚ2k���n���[ς��	U*�~rd)o<_k�Vx�n|��H�V��6UktX)Q�P�O��,Fŕdr4�9�r*v�+�L0O2���6�Ns�%�Mߊ��I$�+# e��G~>H9F�A"���t0v>?UΜ��q8��%GA��F�.R��M��p�,	鬑��+t�xL�9���d>��웧���+���@i�҃8B[�E4�$o�1�_�m¬]���0�g�M^�o��*��Il�h��Hl�:��(����<>L_ɍ��w��OFyf6#޶�;�]H�IOm���z�l�^K	�/o煞��:�9ZяxfP�:jf����캍�3�1a[SЂIʜ�ɬ7@u}hA^�w�����+��s���9,�ޫf�L�_ʙ���@����a�R���a�"'�a�ؖ��&/�Jƃ���'3\J�2���;l��{6�����t�ѕ�D�Sz:����t�`&yl�L�F<�C5�cH_�÷U�@w�cl����`�C,���<�o2�̖L�HП90K��nxD�y�)B���D�_���#�Q��E#�=�D�؁�2f�]=^0����
��'�����|.'=�Nq���<Lsԕ=n�7���:��7lɤϼD�"�y۲`��/ؕ��P�_���uB2��r4-�ܝ;w�H��_      v   !   x�3�4C#.#	fI0D��Y1z\\\ y�H      z      x������ � �      |      x������ � �      ~      x�]UI�#I;g>f�]���cH��v��`�Z(R�z��y6f���c�o ��g��E�M�����s��Ǿ�{���^�^�LzP)2��!Ȝ)���ZȞ��P��$�0�9�v2���Z�g?w]�%�ϳHPY��^x�Ɏ�9���)�oz��,�Gec�&t��g,/�N���d�	��#��8��f!�Ȍ[�� :��T���=}kGә��yb�2��r����{P��E� ұ���� ������Th�[$�B)IW�Iw��p�7X'�zT�tO@\œˉ��xco���_�=��/�o3����I�T1�}q����MU���Ǆ$��
���.�'�&�Z�8��&\��U�Z9D�X7X��>�;��,?����!mC���e��)����*�]z�*mVw��t��5ɷ�p�A���
Ugt�`7��8��<���<�ril�Yn��h�-��y�Ҥ-o���:�$t�H6	�G�{�,�6��,�+X�v�,�S����8�"�I݌Wq��%�? Z��lo�X$�#}�VB�!:r3�p����..#�P6_�t�(� ��T#>r��Ĺ8K�.���R�$}�b�ɇ�T�b�^�R A	��E���	jK\��](�@�m](�ǔ��\�і*'�9�0m�U�	�yO�֢���������[����^fp�!7oM�oU����:���@�f2x�s�F�)�sq
�tN�.{����|��`�_������޾1�`�P9��ْ�Z�O/���y�w��?�r�c�	e�~(�O��ْ��_�������%t      �   ]   x�3�*-V(���估�b��Ƌ�v]�qa�����/v_��i�e�陗��YU7����/��¦ڄ3"�$��xօ�.l��4F��� �E|      �      x������ � �      �      x������ � �      �      x������ � �      �      x������ � �      �      x������ � �      �     x��X�v��\K��缺���f� ��FH�7�I�E`}�����^�Se�5\�͌�����<ǚǚ���)Y�vI����x�qO:6�p�V�,��Y\�ax��u���Hj��ļ̶�}��$��;+29�H⏽���͇�0�c��m�E��֗ �� �X�ծ�Ĭ������9�^7ӻ��JsGkG���R\�ͤ�靵���A���2��kD�La,�@�_��a�<&����'��L;.S�派�V��던�֦^����{� �s*ҝ���n�4Cf	O�\i�ۚ���`�5Şp����ۂnЎ�jb���T�����N���>g{sa����k៭�ńOz;���-ͥ�Jpi;�ʱfפ�T%\����	����[l��M��4��Ys�ͻ�~1��CFu��?4;�����⾙,&��]壨��l�;q�NҰ׾������YQ��L^?��黮R�$���<�\������������T�����DE����9�Qӗ�^�<1��B�RH[������ұ��eV�]��{ӷ����2};"+IoV�a�@/g�1Nv��$]�����O��>K�͊c��1���@M6Y��'�a�$���]!�-�����K�p�R����bV��v&�tp�qŘk�O6Z̊(���Y�acwz����3m��n��NO~W�q3=i�N.���k��I�W�橙�#��F����!۾�u��3�0h�X��]�˴NˍA����B�rϓ��M�|O9�7�. a�L��Ź��R�-�V�ӲNW�b�WI�V�2Ỏ����2[��n�*1SZSSO*�!
�����?�����NO����]��Y��M_��=�I�|���
�<�	8��׮��W���"��Vծ�}�:�ͩDJ\7���Dώ��'pp�~<.�O����m�;Z9��6��;��Ο�D��`���^����1�� ������l��es�x-LC*+�$�m�΢׬WQ���
bQ��/���.����(���z38A�����4�ޤ��/�빾c���7�X�C,�I�$ʷ���?߷�B���UУ~\��>s8�9�WikY����|HH���)�W̻P�jyHD���b"Y��΄*6��]����eMA���5}7�Μ����
8��*��;�����I`�Q��������"�`�s�����kZWHFu4���ؾM�g�*zI������8B3�1�S^+M��w֫��_���7�A��j�7�,A��:�9ѺRL)��V�T<(�����T �+���iZZF,�z�n������2�t5+�z�cدN�� ��TT���,��:���rF��"ҟfߓհX��s&t99����`��7���&��޽���I��ٚڃ˛ ��z���+&��0�O<�o�D�,W�+A�9��y�E��J��4Q3�F�\�(?�me����n�7�M��7��fw(��A�וF�`�.�o�UGaW'涓$�h��R��Ţ���km�r�OEuJ��b/�i|�C�6��0��=���M�J����0�Ny� �����yK��~��MLq.q}��2��L��e=�Q�갮��0�^�p���2��M>����GqX<0��%5����
A����ڜo*�����`�� c�L�a_�hz|��&`������G�B<�60�Z&PrmR�޶|�3��￸�;\��$�xD�{F�n��.�3X48:E7�]nD���㐴�T�m��N��ށGE�h)�F�혾�7,��9���-`�u�Q�3�����d��w��xF/�����]F͓�I��+�:��1	���Vy�/ӃO�E�R��$��N�����4�L����ߍ��ߵ�,d�xF���oҮ`�Y��pj�2��S�V�{'\[��|#�`X5�l\�XD]ǜ��֘�����y=����\�5O9T��z�!��_(N�Y�r8�ʼ�7���ؽ��p|�_�>���l>��|�+ ��~#
�Z�Ci@k3��!��4�>
IX��0�4�/� ��U�Ox#���@���7VyX5k��ન�
1Zl@�`
�u�0	�j���\J#̸I݊������Ƽ�jSZɏѹJkcP1��^򋧛��xp1}}{7Vq����rӟ��
� <��7���Wr���,��Qb�<|��@����b�).+�.f��$��{u�/����b�Č#1_؎Q���)�=2V�&z���#w`$&)�U��>Ɲ/���IH�yg�}�hp���I� Ő����S�18�C��g��e.h<�(j�7���q��^m`	��Z�1��/�tv#@����P��l� �,��!5��&�]!�Dms0Kȶ���QINv�-{���:�n��C�$�Qo
rc�I%]�9�P�{�;�D��왿*x�?��@��tk��\ʲ���f��_��v?�l�~��c>�Ëm����`�J�K�u�d����8�QB�;���%�D�V����l�$��6P�9ͧ���q��l�n�y�4��~��~���b�*m���� �a|;!8Q2�S�P,�Fd��s[�|�n�Ef�����{Xpw��~S��N0�5�C�N4�?Q�D(�x���#�H�6���+`wЌ�^����}�����xOs�g���Ȝ'�Y�o	�X�/:n��821Ҭ}�	ń���T`�g�J����snj��OCC�{�=�	�M�������]�����Fi��X>�l���7'{�6�~�nͨ����b�-`�G������m��8���      �   D   x�3��/)-S�M�K�J-��0���{�x˅m�^lP����> q���>����{8�b���� 1�"      �   2   x�ɱ  ��+�Q@�^�?�dݖ9!���-enA���fME�����      �      x������ � �      �      x������ � �      �   y   x�3�4�0�¾��f_�pa��89c��,�2���9��XZ�����1R�)�I�
�%���	S �_���R�X2�MNN-.������V$��@�@k8��R �=... �)�      �      x������ � �      �     x�}V]s�J}���;s��<JhMRlg��k� �4@��JkLl �4[���JGGG�4`���<����s�����h���$Ҹsx�i4�4>KaʭtL*�M���&w3L�	&�m�X��K3p�sG5��<R���z�Sph.��}SM�3�ǸTL+�9�x:�K�ˇ�S�s������QY��P$�"�����<�h��� �e�����n����i>7�E�c�A,��'��I�QI�C�m�d�/��
Yr����Nk� ?�A�`�d˳e�$�V8ˬ����V�����<����"�Jx d@���ݦ%��W�����t0�^����[�aأ��h��tp߻�'��i��n=�]K<�)c��F;"�/����I����}=A��f3��9�$� ���{���=3���gZ[O!���[��7^�S>o�����|�|O�b��ۛ�O��\;K�$Jz�l�9��/?Ds1[�zw���+	�ĐaǏ��=�!�N���}��I�B������PiX�J�;&s�iV�0���5~)쌔ZJ��X���(�?�Ɉ�Q��<]��G�����UTCSLp�����#�OX<����eRd�pm�/�v>���ۡL��R�L1.�E*��:U&�:��9��
�/G{�H8a�}��Qyx%�J=J, Kk!�D�,�� (����(�l�d��:#`�dxc� �:`�d�JƝ")�y劇ؕ�{��z�1�h���y�B��%i>��^�g�?��k�<��r��w���)�	�V�\��B0j�ʘ�Rs+1>x�|�,_ /�<1�ͺ �������7�P��F�9�
')U�mA^� �Ö���(ߴ$�r�&��K��T�u�T�J+���}�3�	��L0!��گ�G��:��@�р�$���B��]gF	Eg���f����H�����{E���\J�,�8�G���7Er�5�\�!���:_S[��(��Y�fo �ǏM�yk�4��A ���h��W��}J�D"[]����"�@�)\&��Y����ae՟��q!���*+�b�Xߙ�'���!D�1N�t����o�y ��ѴW� ��ȉB��
ƫ��{��v�ۭ��sb����R:J	,����7(��0�h�<?I�j����Wl��٢��d�g��<�t�*����¨v��@QXG�oTZ�=v��O@�v0��+y�u��CvS����.�񻤮{�+�S���hm/Y�U�܀jJr����M
��yg�rD� G�+_v��4�Id��ż�_�g]�t^�<�gq��$t�Th3�j�a��is��OJc�b���h���g��oP�E����{��oe;��b��y�Bn����׭��W8������H�HΥ�����z9C���Rpb����m>+�NBL�(��#Ƌ.���q��TH`?`�u$�:�0WPmq�v�N�����h��:�xSl��r������&Gg�� �Q��+����\|eP0�i�%���F�ݹ6�2>�����_B��8�NZ      �   �  x��Y[s��~F%U'3�����  j_��"����_�� l��T0H���w��TdE*�ҩU���`GʚM�����(��\G�l�*��Ǫ�:	~<T���Y���hX�~�1u�"���!��͠�����d��'��*�W�w>�efd�t���p��DǪ��c���z��&l	�����Y��l_��C����ck�-��x��j�U��
�t��1���U����o`�dű+�sK>?=n��~	������#���n��\�~��;AU�����Sw�פh��YR�D��D{�������t��h?�wy���ݝ�8�jN��D��n�ھ�h_?q���/5�����T;��<zbS��yͦ^����hP��h�[4����趵�'�wl�1iH���J_6tƈ�U8�f��ߌi˾�QD�4�y���)O�2���/�y�~��e���.�4�<����'|O>�dz��(��U�U3��e+K�x`:���r`Ǹ[�V��$�lcs�PIpe:/c�[V�a�%�q6��$� �c�?<G�����PG����z��ۓ�(�R�jc��p�D_˺3eiR����t���a��v����8�U8��fPǺ��?��X�G�d�i/4ϱ�ұmG��4��#MuFSl�4�?�k�kI�4�F�]ܯ�ɰ�@�}@�yXL�������6��s1��q0Ч�vS@�N�j1W��:��X,1���Y��&��6<��n���9�1Ӧe���7c�� �������v�Ƨ�X�P���.��%��O�4	���nN��X"#(lh����mz�$Ⱦ�AHk��Ww���?ex`�=�fC��vo�3d��.53ƾO�zB#�/c�����'�};��ƨX-H#l�=���4�����@4 >( �D]j�!����Z���'��kEo��KkH=jޡ���\^O'�Wl׳}�u|CoZ���1q{==?�HWS�@̩):# ��,�4���/L���Vv(37u"��t^8p5���_��]��1X��%>bEQ�9n��T�^��4׏���H��Nߐ���i�.��L)l�K��}�P�eF�=k-¡����ڟ׎~|��/)�;o�n��ox_���l��xY��aJ���8��V]�*�c����ä�=���v@��~Ϣ��w�0�ʋ�굜/|�M�f�ڦ�Iz�$����3c�1o>� }8sִ�$����a�z"�������B��/6uG����;lCzۚG������iH�l˪t��y�֧	mb�&d���#���;���t�2�~k���?��� ��n��r�PD�H�����;�3�	]�0qt7>7|9��_Lb��Sˑ.�����0Ɍ��C0����N��
������Q9!���M�G��C',|�z8?�f�տ����^�?�:��l����J�<�C�x��1@�-�;��ʹw�]aP��q�I�Lh��zE�c�����Y��:���L��l;�W�Qp�Mj����q�x��n6���Uv6�(����DO������х��(�o1&�WFC�NHV?�D�o�S	K�'�{�i�$�黮e�db�`%���a��3_w�_�k��3ֿ��?g��Io���:ȯB+`�`s�fYD���x�b���9-D�$s��,�2{(YE��\W��5L��wk��@ލh�(����>����D��:` ��`�!Ӆ!��C�����˝�a�F�Jy���y�8$��,�U0����B?��&��O|�3E�����'ήdW�j�4Z��2[0�;�txhOY&����&
�I�)��Z�K�-m��%9K�|{�l�$�@F���b�.�����9KE!y��mM�3!��]{q�Ԯ1�xi��8���Pc�Z�'��R�h4���0ڢ�N���ٖa����=F�[ ����"����D�-���X���DO|�x�=�1��#�����_OFM�$Ю�}ߧ]D��B�:���sq�i/bQ�%]a!W�E�隷��^�c�d��ϝ�s��l�����٘���(���wG��i���x'����s���˅{��m�$��s���N�`��m<��W�^?r��cͮO�77�P*gD��F�Q��l;�m�^g8Nc��l+ev�Z�cYX�a���
V����B���}=����w}v��W�1m��(F��U%���wA���f��!Z���J�'-Ɂ#|�%=!a��-�,U�Q|Y���[����g9�3m���
��HOW���쬧W�|g%~D���L�bk_r1�/K� �Y.��^��^��!r@j���p� �8e+%�oX��կt^Ks�a�H�zz��͗�2�OF�z��Gذ�A>j{��%�bfVH�g�GU�2�l�ܚs=)�fa�jO�-~#�=g������(���t��D�Ȳ��ޯ��]���M���=paw�(j�"��$/�l�i<� �nr!������4y�cb��K$��i��J����<;�J�����;L�&�n@�%�/u<DKHTp V�7��1�1��!9����:&����۪Eo�3 ��
.�%��2"���,1Z'E��hc~�}Q�@���G.0� _wvc�_���5O(�����r�(Y��R2#Eg�4"��@bH�ɵ_ ���сдX�����7�U-#K��)�_H}�6�-Ֆ�v�I�as`@_�l�h^��#^idP^�Y��Q���r���3��H�Y�&���4lv��FP�	�?._�$v���NL�\��?l���r\˰9E�>�nKEE�t�����ɭ�_�\�ț�0]a�|ذ�}vK���Ⱥ�ѵn�m8+d��,71�M�z���ٳX8�'���t�����Z>�ʣl�{�uyW��~���ϫ����9���N朗��B���kn�,��#n��������W^�e	�t�� ���g%�VVY����Ϻ��#l�^=\��| ߬�J��VQ�V;�E���#�6�]��ι3,J17�%�����)���t���p����>
����IS6n� [���|�ʎ㥿��lK���u�F�%�*mp�O$�vr}������,���K-^nhk�cF#�d��ί[Ԗ���;��L�v�u�G�j��u(�ֺ�9 yvѥ�/��`I�f�pnM�=��P&J�����M��K��R��HA���0ۢ��_��py��+��(��.�t�N��#��t9�|-.��-��isr�.s����ۛ�kno�O1��>W��l�Tv�KݯE�+>�P$�TJ�s���:�)=���;���_�~VO	��nM�v�](@L�o>�s 	�آJK~U��G��������:� De���oTa� �{c3=����]8'*�M��b�¼*N�h�����K��x��l��RAk�ᰵ��i|X)��Q�EO�&#��3�(3 �ߒ��GI�*l]�KY��<�p��:����1��߮�%�q�6��)V,�{�K�z>�aor��q��G�ts�
i���ˆó
W\ͣ 6�#5�qZ���m&���3��\��2�a=92��}j��Q�:�i#�}%��ϕ@4�bqta���;��������ކ����M��G��m�l��ץ(�E<b��ޭe^�����<��U�f�hT���0'A�P�){��l˖��2�<(����\]k�Vyͫt�C��иµ�F���{�R��U�4��au|��d)v��beL:o�f�K�����!W�Tvԕ�cJk'����>���E�����DP
��ն������?yі�      �      x��[�r�ʒ}�~�D�.���� �,p��8/��$���]_?+�@���}N�LL�m[(��2W��Y�K������ˉu[��v��F�X�e�cK%�][��r'Ŏ���h)_�||JK;�B[���7Goj�g���?����ȅ�x�m���F���:��W�6u�0�P,����l�N�n�P�Ѱ�,[L�XS�a�f4�No�,��O��;�}�,�����>��I�����_u�U�:��گ���~3Tb��X���MՀ���H�iш�=���i��#���6�b��i���4,"1������06�N��h{�X������c/�x�w�����xp�lx����wF���wQ+�:�lh�~Ge��h�q͚�?�~F�d40ޟٰ_�3���)����ꭳ#��6[�G�go����zy�&��ږ�k��l����O�v���mݼU��uZw��R�ņ?�c�a��h8ά�ɧ�vT�k��k�>�ܮ'���#��������k��f��n5ukj�O.tɗ�8_�ڧ�I��eI�h�3(r� ��>�T�K^N�=K羄�G�}�mG4H��\��-�M�)��m;��~OZ:���}����X�(���I{��ҎVe��ͷE���ٹ�B�R-���&���y��39�����VmDOOM��w2Tf麬��j�O������9��72�޼N7����?���a*��%��%55F�S�wq�Xf?�|���A.c�(��1���T���>�:�yЄ�uQZǥe�cn����9g���� 0J�D��&����U��$�y��u![�}�=�[㛙\c9�/&���uϱc�n�������ʺ�Y3���*ͦv��l�����}?ŝ�0�v�В��>����c`�H+?I�<�J�N�=��(Ԁ�Z���pH\���vp(�ߟ��46�Qm��6i�b�]"o��-�ryM��>�|yym��w�/}?�g���Z�����u��M���OŻE���v�`}�5��|�=��Y�p;�]W~�>�e�����KuY�x�ceUx�/���<�"�h�q�����c�|�~0.�k:�!)Mg�)��d�����2��H���#�}�y1��g�����wŦ�� �,��v c�k���iYb�{�<)�=�I׷n��=���ʒ"�zӐ�
��ٙ�pl�aA��7;GBv�)��S�ݯ��~�^�v±:,���vG��--v��k����L�z�����7wmͿ�?>[ W��}Г;���g�!+�xk[���C����[`�}�F�9a.�����M��qPR73o"�d���<���!�o�79p��>sT�m=U�T�d*zS��Ԕ���O�m�l<�陲r���_�o���x^lp�D|�������+b�-��Ӱ��@���� �f�&5U2@1�$ �_ce�+Xi��u,KR��=�V�6���A��")��o�3~<� 6��T��������[�WK�L����@-,�%�}��[���E(��;uZ0K�B
�u�ݘ�G	�W0x!/K�J&�V�u��U3������R3'8"g�9f�֥�x臁����SR$I����/fS#V�4N3� 	 �En�ޯ��f���U�SD�˗��J���ΐC0�IY[���|��t��r�<��2U]6���P�c��.˽���ן'��5n?���&وw5��y� �7I�u=���o�� ��UXF�{#�݈5Fr��Jd����|���(K��d����=v#R0`�7�ڎyo���w�7�:�\a7��̶����Ы����)��L����+`Zk�J`J�E����B����Y�9���*�b����x����S�͛�D�[�hrz�fDRO�f�O��2���2�?t46�M-l��Y�xt��9�\ڒ#H��M�۫��C�eÏ����ɖ�����ah���$�=��p|KV�F7��lM&�q���8����#�����?-�I)I��Vx ���2�;�W� ��f��-S:�=�;�<O����}��w=ƭ &�&��9?�XҎ�a�}2�n�g��T�`*VQH��P��a'B����tp@�H���V���@*�xN�Q��� ?Ѷ �_D�$4��`%��g���$6���dd�}����@������bC�u!��+�����Rb{��&隦�M�4��}���졁�{�4�TwG6�Ӆ
.$?�pI�V�����L��5��k�)�I�[�|@��!�'X�O]��z|t��i��AH�}�S'�B�D��_��!E&`�זӁ�#�dÞ�@�8��\��hVuM�kh.��������E��${%6|���$1�EB�x�;9��K��B�WMSQ	��|J{$�� �N����I���w�5W���b�zu~	B	��p��Q8�[_8p�l�R���Q�������9y:���;P���x��	�·��/��f�i˒^[�t�Άvœ���kׅ4�0��}�NО��b*=m���M�����\T����JSI^"?�˼X�3B��*�7z�Ȼ[c�z�x���O���E�1����W�<1��Xԥ�h�ܯg�ٿx�E��ɏY-@�MmB�[��#E��s4}����Q�A��DQ�Q�ӆTA����,�k�)�1,ÒYQ����	V����O�ۂhv� �pm��
8D*���/؟ބ��kF��%* �Pe7@�t
�}�] ���#�/3"p5��2!��oO�BETR�wZDDz7ӐQTB��n\$�\��0�g>#I��H��4%>gF���޾
pz���cnk7������^��/,�4�"n�֦SPz�?�%Ӱw#u��È�?��)��<<?'��H���G�$�d�Ҫ�K����&��B?�N��Ug����:'p�[Û-�O��PU]U�M�q�6k��c�{^�l�.1���s2M^@)>َ��M�|�p2�����!y)���k�ٳ�������VӔ�>H��G
��߳O�w��ޒ//�.=_�{z��R��q�L�_��Q�U:L��'E���SToϴ<�ދ�	e�e-�(EA���k��+d�bzK�Ӫ�)|q�(�S?�W5)��J}C�L�#Eު��H��9�32�~ >�� *���S�b��a�+G���ϐ{a9f�'T���������@G6�&��%��AC��']���_�3D&5'`m���f��@]�ݣ�nҧ;-KH~���ɱ]�����b*'��}8!����<����å,Ad�N�U;
{1�M��JsAd�.D�E���	���|T,��{��6���vF1�L��� ��� ª����91Mq֚��*A��K�\�'�|b����h���[j�����z���%����PT�ab}V�9�f�e���XZ!�;�D22��,/k�Dcp�r~|)��fF�)X|�Q6�蟴��-�U��.�Fd��Y]�l�S��v<�����p�Hd;�)�5[���'²H4�v�x���I���@𣎐C��yI4��4�J�S-�@�.�<�y+[�ڍ���K�"jkO��LU�$�)I���8!���¡b Aǉ1��G;n�(��LïW9�J�t�x�����E��e%�����%�0�d�&7
q���nA@�ż�ޔS�ZM�qw���J3�sRI�	�!���@�vɖ�����HU�rQz�4���J���S������dj˿��=[*��B���Hٳ�2TIg���M�	��'�k�T/1�)J�24^rRl�I�j�o��ʥһ,��v [ǚ��&�6�M$1�	�.��Y:�U�d�iL�������]�]o�e��L�����&�3!&���!��?��'Ԯ��Qi�ym������Og�?A�/~��@�R���{�W%���iдh�
0}ɿڞNMU4S�-�a�9Mе�����ҏ���,p�K�{@�W7��UoNux�p�+\��Zc?6r8a���ӯ�]_6Ɗi5-N�d������.5��'rq�e�M�D�*� !!b��a�e�{QՐ$��eH�K޿��瓖�u �  цͫ��SRZ���{���{�aA8�����n�:xO�̿� ����2�#�	���V�spx*^��6؅9��4-�X*�B�����	9TL�H�Ⱦ_Є"��=p6@ �p���2��K��y�kJeQ�\���H��j� ]��
��Γ���l�9�1*�.��)������֘ߍn�a�1��X���r�TN�'e��]v�$�x��`�ɪMn7�,e�r�?h����EP�RT�4,�i�ҝ��nt���%�$���B�uK6t,=)	W�ۢ��޲�, B��Q>'#\�qb#��`F�ݡ��	1w���2Ĭ�tl8������^�����7��^Q�ݺ�������I�|�k7a\�Np�:\�xk���s�ve���@�e�G��bG��Og�d�h&�ƶ������hXg#f)��=<�6�'��?	~0���Y`O�(��v�Q1�9+�kϪ��")Bz/�͙�?��h���h�إ��H�G�W�"�J��#Jy����I%#s>a+g?���|R�_�ء\+������OU)D�#��AKU�m�ˮCqR������'/��������%J��Q7-�k�Sת����>-�D=MQlO|�ØQ����$a�jo�i>/yg�o��e
ig�\�@��n���)	R���g��N�G�Ծ|���|?��Y<oW$����;&�p��_0��uy.1M��f2Kmk&"!�/$�H�p�i��pË�ӻ�:r���=�^HΙi��AZ�\� �.�?\!�Uъ�wŸ��$�'����{^��MEBV6uDWp�Q�P񜧪	���r�wx��v���w{ ˡT#v�RR�?Y��SԨ����2g*S�/��#QL�p��(���-ͣ��d�i��;
4�#O�6���*����b���R�\}:�"�uX�+��^"1�d;~p 
)eT'?�E�(~���4 Eʹ����2z������,-�.)Uوu�D�Z�f���e�~@9��!
���x0�Ř?��Q:FT3�IL�Ȣ	?�w~��8�E}-�	w�ZpUϥ��o�F�:�TX��֣ù���c����1�g��:?Eo-����5��d뇰5�UX��_�,�RM��_(4�y�tgO�Fg���ai$�eu�N6!�y���|O��˴��ǽ�H�4i��G]����gA4G�
K�����]Lg�|*R���H�MA�"J΅�hr�VR����AK(���=?ZR��\����s�Aڂ�~BO $q?��:����ݣ|,�ͣg�\��
U��Ti&�����͔��	W-�]p��]-8)��|t��wͻ��q����,��h��U�X����ӯ�'����KJ3K ��O�ȭ5-��,�-�&X�I��h����ͪ�q�&���	�{�@x[�gi�A9������4�ƥ���Ɉ<{����gd�/�WB��<?�Ύo�k��h��"<������0/�V�&������	��/����2U�7���y�8	(�b�,F��� ^�)L!��:u5��UC2k
&�����g���v��&{��?�����^�D��$��<$(���g��	�� V����؟��.��������sኴ���X:o��Y~w�wugI��nR+Fb\(�J#.Ǜo���N�;�;^ě��~�'��W%�l��^'1.�T�T��x^�ܸ݁����[��|H��yS$������m��t�w�:��<S��x�U=���|�E�[r抲*�+Tiq�)?f�:��K�d�G��b��0r?	�'Kf*��6�6�礋#6�58s�Wl����8O8�q|�j|相�Z��'jJM��3�\���2������o��np3!��3��Br���gs���+�"M*�D���Q��Ə�����cPW*h�$��hH��I���)l�g�'� �Fՙ��R� �h7�-"�M���1�$����DY�f&�QpY'b�Z���I�����ߒ�+q��ӑ���c=���i
~S��F��Ӭ��~����J��4��K��&���MXo2�I\�\����^ i�����"�=��W�`Ip��	֋~�|�J�e!�|�7�)��9�6T�Y&�� D?���?����b{�	�T}�Қ�V0HJ�3Gצ�9|�>�I���ߓ�r�W-T�'���j�g=�����2/�q~��:ا'=R�3�봙҉��QJ|페]�E�7E�25��k����v�!      �   "  x�mUɑ$I|¬qC(���rQyԮuv~(pwH�}�?�I�8��H��I@���A�~�OAJq`��x	WʊP�Tb���u�F�r����$���Hmt�J�K�F`�3E�hᒢX:�@����D-zN����щ��*�.+g�ek�
u���JuN1�&Q�R����n�TvW�)j��������.^�ҹZ؛�W[�Æ?�bcF^@M�Z%��SoYI�	�/�yU���s5�Pl�r��y����F_�Գ豓����l��2�����I"l��,R�X,%n���>}����@mr��Eu������eX�O���U���wa9�ɻn'|�%�=���s���
+���e\�q��:�{�:�"mi�i���=�2.�!S�%�,��ײ�ځ�oW?�]�G�?l�I��t�C�
�.��d���0�$E�Q�6S+����b�(N�{D������[k�A�x��^�	�	���^%�̺;T���C�"���9r��;'r\���,����������]_�߳�ԧ����I�䁍��� ӌ�������Y����څ?�ޗ�n���Q�����޶�K��Q�Ǝ�?�q�Jʰ`3�E��мƯ�GY}��]��}9l�3?�CrTs�e��=�u���E��u�m�Yn�����<�_�v�V��d#�b�N g�X1��+��u>F���Cs���^d:ї��xm��N�e}��H0B���׮��ې.1���T2��d�%����>s��hSW_~�����iR�v,3�0�p׷�0��T��w;��߯�� �/�,V�      �   1  x�u�K��6���3��]�f���R�4�8h4� WK�Ǉ�K�ũEjE��e�럿�0,�W:Wx=ʳx,ҧ��)�TƢm���3s~�b�1Y���d��x[�J�H�G�
K�ſ�QD<�����"N���Z���B�=z��ȅ8e�����W4�z�i�旑0)�<
wn~Ԍ��t�`zd��BPcn��oNIb��SsvH��b/��6ijԎG\n�������״=�Z̵Hn�Ks{w�+t�KY��4�Ћ�*����}7��+��V=��6#nֹ>=��9�:��Q�|ɨC��v"w����ܲP��j�ŗ��T/U��ӬiἨ�<����i%��)J�V^�R��M1��87�G��	�a5cuU��Q�S����Ȟ�56D�=�(��$Zf/4���� �G	�s�xL�� �w6�$��c��n�=�Rٺ�n�Z����S7�Ŵ��K�ȹt�w_-sn/�g�.:���G��T]��n�G���.L���[���s*��٘�q~�s���\X�X*���ݥC���`Q�n�[bS��t��l+��1g���=�:����B�/�q��u�c����;��d�6��L��܂�r�hbAOKOģ(�`�X��[��薄m�mC}�#��L/�X[�6��a�.Ե��(϶ٰw\����[��c����1o;���>B
;|�X���4��K�,m�VI/G�ɭ��lې���1Ygs�*=�ڡ�N����G	��������N�/�[�ꮤ������LzI���}�TLZ�{+���m��ͭP�=������.9��      �   E  x�-�ɱ%1�c&�ʗ�C��e�(Y�����WRZ��L��oB��5���� b�h^ԣ�
�'b��:�����84*90^41���91����H�`���J�<j1{db0�#��Z>��Q��a��ܘ�ϣ�
s�S�Y?�8�$bP�\]x/N�%�Y|�*q��w��$��$�{�`�;�zV�x}+h�E��wfoI_��nXnR�EHр��hp�K��Di�T,N4��ؤ�Z����E����1�U�@V�4�%���&o�K�'��đ�o��қh�қ��>/��r
�%7'��x)�զ떿"�� s�      �   $  x��Y�r�H}�2ӹJ�? !S�RH"�E ����_?禰e�T��D^dIy�s������I�|΂�ܹ.tw��w�UV_�n{뻇�k7����k���Y���y����Bi�j튊4x�bNw�q�A�`?U�"������8w�+Õ6���3>s�h���%lug���e���D�^��j����]�
���8zL�K����ا�rt[�Y[�V�V��af*0
���ew7�?�[�8�+�����k>���'�>^�j����n�eI����V?��udF��tǅ8W+W��P��
a	�G���o6}�����^�%����+��0��i^1�̹�?;��:Y繷��%a�:�}�e�:^?���E��nPg���\�m�w��m��_���/Aզ�[�z9�+5x��MPe�z��Հj�{��« -�\;�)�ѱ��qVЇ���_Wfo��w,�W��� {I#v�H�s��_�T���P>;�*��a9Kbr�Fξn{�5��Ľuٴ�>���G��y�:�MY�s�M�;Ƿ!\O���?Ǔ_�0!s9� ���u¼L����c�*���]l���ȯ����o�n�����Ӿ�{���E\݇��,���»o��c|����/%���L���P���U��,fxvccq7��Ӹ��.3m/F��&����D�V����=-��W�2�p�\�} 5*��I�d!^�-G��(��|T�`Y&�٤�om�e���ϑ4Z�ݘ (y�J������CF�0Xo'��H���t�T壈�IT�0@�-���R��>�<� ���a�p!pu4G��}7%�!8���@�>B0c*���i�~m��n���J�"��:K��Yʜ)ߗ��~ť<�|�Y�j�b��{[6���h���� ��9��H��/��g��23�+4:��[K��v
M�Uq���~yO�"���&Vj���>sH�u1y�;ݖ|��O�q�^�G���\�� Tє<��Q���-t�;kܜw7���_ˏ�������)�bJ���!��z���ڢ�/��JE#EZ����Ȃ�.#��A���.���z�0�n���q_�M5��j��}4��R�\�#�h6i�V��uxxNo��V��`p���h&�xj4��+�e�%����^L�ݧ4n�@@��Ϗ�Y��(Ĳw8���>s��� q 	�FN��ls���g!��h����h	�ƈ�� ���uj�,��#�D`�0ԋ����1Y^���'��)U����ҵN#`����[$"�A�]3�XV�=��s��0|{�S B7
e���z!k!Ռ�N}9r'2̧�h��CΆ��'1����4 �b���C2�OY�g(�B�#0�&�)/d^�)=� 9Z'���t���"S�בe©���KkʞNt7�Y��R�&�Sa���p�Z
�B�f�������S��'�G�����'̨&#׮q+�J^�nť����W��o�\Ӫ����3A��G����+m�(���	3`��~�g�u�ȍ�\�K]�9� � "q�?@C��n���a���Wom��等Ӂ����~��Ւ؇~Ns
�=�n*+F�:�o��*=P0��B�X���z��T��|���/SA�5b(�V��k�ي(��=o1 �ZTK����@3��S�[[�,'�J����4�g���c�kg��6�"��k[[���x��$��~�xx�.������"�=_� D��o�l�Hߨ�E���>M����Et�[��W�ZX�$ r��
�|��!�x��A�Hu�V���^��=�c�19P#m@�9Pb3�������^C�7A�D�Y�00�0�ֶ�?��8����H��0|]�֌)���
9���N,!�n�r�/fb�zs���URh�UG��B'p'�Ȋ�eߛ�7���w���n��^Z�7}��� At���l*F�}���M�Q�u"�R_�ȅ����s���m/n+b&�(������l���Q�<<�:��,6�}M ���#�8u)�/�2 �`9��=���J�,O>,�N�k~aD�J�Gn������>��fk'�j�kj"W�a�e�N�s��\e�K�ȸͦ�"O ( ��P���U�nhP������?�������=�]�,nn��:�g[��{25!C�Q�ήpx��i`��Y!�P���0p���umxg�HC�|z��--�s--�Yi,���h�E�
���ց��i��Nm~�'f'������N?(�$R\���R�.se�F�{N� #* 5�^Y�$` +t�W��{�p��޺װ�0EA�4É;�XM��»T�I|��=��t�������"�(q�{ƭ���;o�{~ٜw{���eI�T(�6(�τk���`��?�|��m�w����>~�T�yF(ף�-�J��:��|��>�a�E�&��l�7��;/l��q���I��/L.��S-+�~+��޾���ٜ�ݽ"C_��/w��t��X�F<-J±�^�h�ML�C/�Y4Q+�d@�e�s4ϊv`}'�x�g���_��@Q�n"5'���'�d~T�W��� ��	$M�ށ�E~w'_��0υ@ք��;*YH	u*�B������.o�k]p��X��ch�+��*�;c-�Z��F�ik4'�Y�G��Hy)"?i�Ru@9M�$�5�_o?$����U,n��{��^�6?�P��6�z��t�?,W&�T�V�ta��{�U���&Q���h�����=��ܙ>��gk\�JJ0�'�����[�f8�Ig������o�/G���ݝ��q9ꤍ hb���wi��bt��k�G�	Ő)���U�P,�B�N�>�ے�%2����"ޓ�B�ܵȿ�:��[S�/�h١���Ui���$�"�vטCaށU_��?���%���:�Kw|�����l����â��X=���ҩR�;"麮�����?�*7k�ǐ�+U�M`&V�T��U�� ],K�D�W��Y$tuy�f��ޏs'�K�F�[�c�E_����������b��hyJSR�j�ޥ�p��@�T� ��D5i7�����bJd;1J���N$���G?t�,�T��h�n�&4V��Bl�r����\������?c����>��N��1$�qO�H�Ӌk��\�)i8ĳVhOF��j���$?��0l;��m������fC�n�9_�Ŏg���z�z�v �o7Ln�rڡ����'9)B��X�Z���К(��;O������u|�A��RŹ��i��d�\&�ǰX5��C���fw?������JsS��ߕJ�w�G�      �   �  x��UK��H>�_و�z�C���[��H�`�;��dڻNL�e/U�U���_�����օ/�Βf���)��Y����O���l��So�u//.�P���w�k�
jA�4F�3ַ��f���ۣ��-�~C�,��Ч6��C�Cf�2O�5㫪{�v6�^�?'㚾��,�*�2aR�4-f0��zY'�����5��&c�c��s��f�\��.�:���������y�ج�s�fR��p�[᏶�x�Ow�2�-���۱�e`΂�7tz|��ש�4��f�T�I���٥�w�5��4e��$�q�c`����4���U��t�c�-���F6�Y���C)��܇dF��b�0ih��i-v�j��,�!���^�[fs`�h�Z� ���K�ۄ�c!Z�<P���t������Ρ��$� �g������tm�a��;�����/-SX��ȤM����V�r�g"-���`���ƴU�ƣ$���I�ï8��ے�GT��b�N�w��d�M5��`	��I �t(wL(�4(�i�/�!��'Ȃ���)���A�a���1��+Q�j���=�OR�.�kV>���iu4�������1�������| a�ߢRz����٨,<����>H �&ϯ��7�����-�c�6�麤�f�c˓$(������ `j�v��:Ƥ�
�0&$`��b�/P���c>�i���A,߃7�)���6<O��t��X%�W8�8u����c�3D��y6�>�$���{�)-�a���Tw���D܍�=�}R�w���=/���}o����{{S�W'E���1I_�	S���a�c\OqC��e��Փ�,��C�H��J���(�Ks�-�*.v��־�{�m���~��O.0�!��L�7�7�d���O�0~G��S      �      x��ZYs�X�}���_i_�`a��b^rK�i#�_?'�J6���'���*L����r�ɼ�zKn�&^�ay}ݖz�h�J	�ٶ���oϗ��:�z�VM�x���
\��t�2���x�ٯ����>�u>��Y� Y�-�6MMk�2�%���	�8�v���Ѥ�!����a�>K��盟Ϻ���#K���+�7)��3ǖF9��2zM���য়?c�o?��v��2ΥQ:=U��M��'~����`��9K���Xa#����}��G|~��.=s�8&?��a��4z�i�k�wq�F~n�N���X���m�4�j�2��%�����,��b��'�`�/�`lʮ?����z��xsIZ{�HC�Ȝ�6Utasv�L�����K��-uo�����_^;�������e����X)��+�x�����<��]pL�>���װ��xPy[:��2��s[��P)�-��{K3,U�-�h�V�,���LN�����s+v���יWy����;[�&���b�-��P�y����Z�%f���\�`=�5U����%�.%���j�\m�R���`\�(��^�h��C��R�)�4�THC�4En������R�]/Op�eX�3�1)^�-eQ�ta��Z����*����s�C�\*��n˪%�&&W�h�-��ŏ��*�%ع���&��9y%pM{��2�"�d���R��J�$��`����TMXg�Ln?�,�S��
���S����WS�t�n˶�rr%LV�ϴ�U8���4�S��(��r^=g4�M����[ӌg��3o0=ٔ�)%ϙ�8q;R�!�?(!mJ2$�A?���QҜ֪_4�XF<Sz��4�Qgכ���?�4 �,��.����}FYj�o���}��O��ab��H1�!��i˦��m�Ln�|H���g�xj��c<�X�D� ���AC�/f]���Lr�!�G�,#a�m蚡r�|����w�/��9�Ɨ���{�U�婥ږl��f�q�dD�Ӥ���H# ��F҉#1��yqF��&y��&�O)��5{���΍�>��6����%!i�R�VV�5��U��p�<U���KPֳ���'4x�?z��ey�$��pX�	�bꖦZv���!�i��-�q��2G�i-�d�q�Q������x4�Sz�͓;k@&��	�$_'�ɲ��Y�V��.�{jk�߬v�L�<����_�/�پ�+�֌!L޿�;zVr
hM��&:���U@<tPKo���>����H��"���L�X��S��RH�,K�L�jkV0�R�y0^�-�Jt�6���4�ᤶݱ�U��������n����#�4�W��-W����VUa��˼^�K��[�����O�ՠ���$��Ŋ �R�����i�Pq�Q��H�&h0�U�U)`lr���n`M��}�M������Pjq(�ay���2�߷T]�P�2YA׾����u�_�)�������*����60W��+_*��$���
s�ȐL*Qn�2-l��
��& �ɍG����������_� �Ap 񂃝<wxJ]���>Q6ȏ�:=�K�f�@1Z� ��yn"���>�h�y��hX��O^3*F^�i��(=	39�����O�Uߑ��K斤��ID�$��u��*�֥�u|���8f�`ꌦ�^)2��_���񈮾r)2��uց�Ћ*����'0<,ݯ��vmN��I��U�.�N+��*jQ�쑔#���~P\���|��g�`�X���Q�}JC��5��i�O��r�y�U�wfM�f�wg&FJmU�/=GHJ2�YWHl����i ��A0ҩ����ö�f���h7Z������E���$�0��M����J�a�֛b��=s��xk��P%�0]j����T���[7"K�h)x��~�L�V5 IȢw�Q���83c�������ӽ9��y�&��C�t!���Y9�k��9�h��&|F�.�ϔB�����ˡ1@��g�Er��S�9�z�vm��3�����e�STw�[?��g�J/��f�d��}���ß�
$Zݑ4���;����%�#cg�VΩ7��]*#�ߖ�S�:����16�<��aMI����Z�>�#0��A]��[\���uP\�%"Z�Z!�έY���J�\���m	v���ݞ�>PLCS]��"�h2�����J���j��	�Q��4�j�
6ӿ�u�ƫ�R)��A쀷�U��k��T��%��E���Sw��PA+��HB���ڶ�km]��2G�Z���=���� ��X㱒����"��F�7�o#|�?���lP�#�CZS��i�7��`pׅe�4����FM��c�����=t\^;NV�xko���2(R��c���H�ġ�����$���P�-rKa @mJ0*̯�Ǖ2�%||��a��T��G�J�����eI7LI��ܟ�Y��o��\(�����r��s��h�#Zu�N���*���WR�T�;���	D�j�0pHU�ǘ-A���i��{ 1���k��y���%o_�5x*�ꈕR޷¾�k<G�ϕ�}���z��񻧖WN�+�m(S���uM���B��}����֩
�*�#D���O+��P��D�)�:ԕ �G����e�?�aI&Zgўfóu��dM��Q}����� $<0�BQ������?�JH��OBhJ�Gi�����3�?�`�A]T�Q�q���5�e�����rg���+Y�VR^����
j�I���̚��!j��8��rv����3i��A�n��
�e�����9������)q:F%�ĥ�5�J�ݑ4��ړ���"��(w��Ť�I��>�O����D$ĳu�`c��"cAŢ��L�h���� �|����MD;���x�u�&�������Eΰ�3DDV B�B���~Ԣn�K�EOBB��G�F�0�ߨw!+
�B����&+��a��>.�޷�c��N4#�u<l�
\��7g1�L�!7$�u�tFHz����Q���ǜJ,@�\��5�v*� �+k�'w���
��&��-��8�K�����|�h{�����ױw��P�;&��q��^N;�w�l�p�y�3�&��EL������~X�F��"��RWj p%��m>E�ֻ.�'�$ʹ��7�X]�ﰺ-g��E�Gl�p�ݭo�%�O����'0D@�P�H�����ڇW ����R!������*m�)��Ѻ�p"����b�m�"78Hj�eI�a�H��&����^1�L#� u	 ��;��PA�p{�1K������1˭���G�wp *����-*��+�z�.�,�VI5���N1��e���^�_�/�*�&Q@�T�&ф���!���F� x�7��+�YFPGD�* ���A� ��$�H}&�����-[2U�`>�>(��w�D�L�b�ݎ58k�1K�wD�F U�8���&_2h���pO4���k���=�Ҁ�*���e����T�����)��v� �o6(6dS�%ۢ��,1 Z�_��	�+A�c��/�w_IeP�a�n� Ǥ���d ��u�MkoaL����iy��U��\X�.C@
RvZc�A�Mo	�Ѡ������.���B�*R���3).�$}H/r�KX
ٿ�0�]��;CÁ<�g��sDvㄝ)ix)��!�Q���e�Ӷ�zE8jkMyB�4jLo�+���!��ٶ�_����2&�<w��5�������L֝`��l�)�%hSyu~�,s�`+Pf�����B�/�^E��$��' 	<���֣��"���u�Ғ�I Ƨ�������#T��.��U�	+h�DwX �).[QP$����K�A<�<��D��I���"���M.}� ��`}�!�oTd��:	�t�e��|������6[�*
H�*�6_S��PBy�Sd;�t�3'�1"�h�V�.է�A2��3�ơ�����	�%)��%�}w�fj�!� ��ň�z& sz��	�b�SYw���R�F�@BmC����dM��   �gK����T���6��h�K�K7�2�����dI��
{�C�Vj�r��C,������JuZ/9š�É��.��@�&���4y�Z��mV��W��x@̌|R}�_*ۆ�Z��8Ś�#`׏qF=i���r��������*ԃ�ٰ�U�w�wB*�*alqb�A�A��!�&�Vq���n�m2����U��*q��GEZ,i�В�h�g��?mĉ�*�a����H�p��i�L���!,T���veɪ�k�l���E�JBܻL��d���Q�zj��5�<�,q�3��m�ܡ����zΧQ��?���I�3�?�Z2�6P8m�
B-xc��2\������b1[���ҵ	󐙈,0���Z�_�O�|��{^?�d�^��{�N�����t����T���ƞ.z�ܗ�3�W��]����7������/S��6;��\XC.�߫]qJ���H��>�
��js����&Ӓ�}��t�Dg�͚2M�LB7]G���d���`�̅L��,������{)v+�0���3[Q�5�t+>��Bif`̛u��Dw7)�I�|�������3�'�������I�V\;%)Ζ�ۆ�h�Ҷ�Ѭ��?D�[|w@�F��|�,�S����J\����5�0L�X�>�����n��?c�����;7�]�U^[�?�g��b�M��c��6ձ��[�K-J0q��U��}���+�	��]�!�۴�5ё��}vOܙ~ާTl��S}B[�~6�{�I�����]��+�����R�Re��e����v��yy�      �      x��\ٖ�H�}V��ϙ�R����ٴp�!,Q
;_?72�ZT��{ܦ���r����F$�*)��銡���,U��F���K����	:hϪZ\��A&�J�v��Ҹf���I�rk�*O׭�T���l�P�����ð�ʢy���Aϲe�f��4�:_�V��/��r��Ǜ���Gj��5Y���4i��Vq0�.uр��7�l��_f��I�nQ���R�e���-�m�I��׽���)�05�6�˒d�c�j���g)���v>x����Gt���������W�Nǿ�x9�1X��i����x�_����#��'E7��4�tP[l��^�V=[D���`�c�,&�Y�a��˫��o]���Y�Xu2�1�Um4���A��mc���xV�;5%��eT�h�N�*���ض�N�A�}{9�ǋqroPuSؗl[�m��&����o������ �Ϫ����y��m�P�G�l����p�K~�*���==����W-��j��T�)���kV�2,�4����+�ރ鷫��&�$ZQ_���f����4���0�5�%ا2�]�'a��C�cm2;�������,Ŷ4ӣKR/�������~y�ta�h-��^���à�m���[`t+I��\�_$5{��;�a���_%��a�Xa�Nm�ٿ�`jX �®�-�����E��������0�ٽ��0�J�]H'j��S�tn�0Bݲ�)��ϭ��~e]6ӰI�Q{�b�4��&.��k'!�����-�r��^٥��ۛUi�%�:��orSm/�&��D�Q�ot��l��rE���r{��^����O��Nn�u�O�;�]+�a����{t��<�$r��^�%��ڵ��Lq�ZYnʉ6�ݒEME4t�l��x�7޾ˋ�x��'<_��n�,�M��B]�
ha!i���;��ơ��~��ԕ/W�.V;�4[�tx_q{��Ms��.�F6$��ռ-p���FjG�B>`%yך�E�����0;:���C獃VuӴ�3�N�˅�ټ�����&�T�j���+b?��	�@F�'�X�j�{
�ǵӛU�9�C_�r ���[ig����u��l��%�n��0�ݖeڦf�$�'Ő�1�e��
�b5�u^��_�95��IF��A�"E~Fq��Nu/E]Im�1��*ܐ/ߍśV����ֿ�O^?�yې7�3`[�"��F{�C��ק!9�qMX�j[�eZkq-��V��]���Tv�Ef8/?�Wjf�S4�9���n�8��[�m���_t�j=l���Z�N*^m:��q|ޝ7r�@�^�'<Xͅ� O���:��;�;�M��_��b�U':�}�j�S[Iz>��J1$,Du$�f{��.�ٴz����g��Sv��[TƟ�ӷ+���48�*&�r��գ�,��a��ADo0������חn�Y�M���O16��"�,{8v��	��j������bha�~��%��OG�鍋4MS��(��������%�����L���?��(�32�g�_���ף�8bO��5 _E�T:�V��u�	��C��PϝzU�#7^��1M�K����9|�X�c&�|����E`F|\�F �@�n9�w������'X�I6�P��j��С���u_���������Y�"��˸�ҿ�I3��<�@lӷ;�Q?��NR����[NJ�0,V�M'8q ( �c82�1�"�_6-��%I`5�Џ��0_Y�Q���P����v�DyZ_�9����>)�Q-NG�L���L� �O?Y�_D7�i��W�% ̚���9�ٶ����}ûܮ�?�/�h���Lј�J=߃� 	�CD�����(O�� �ueD����NΝ�n�+�G�X���9�|�`a$��kL6�q�c�Ý���xRFN?�"�ш�tfU��ݥK�� �+,%�6Kfe|%���m  ��`C�b(��1�Q�Yu1M�0Ǜ��~�̉=mN�à ޤ7o��7w�������.��IkZZ�ZJ��u�c��f�z��G��aL���
��rs3iu0ː$M󧟽�v�I#ѭ��; ����l�T]����3Ӫkp�!,�ћf�����^T���1��S�^:�"��N�[���C�ח����,3�d	~f�`$L1�L�H�$�4F�����;���I����?76�nэ�ƅ�k�|4���Y�m
+�>XD%-�h�N��0,��]]P�u�©X�^%'�TV)b�S{�&��jjd��2�!8�;P<C������)i�����KMS��~�hk�G�,qV?)�@k��dŤ�V�V,;�SQ�����Ҷ,tMXs�|�Y�;���ɪ�=��}�bDuJF7�QXV�S��l�Au7V�Ol�~��V*��ںWB�=;wj�w�h�� ў�\����Ѭ��G'Y�)�V�����A��k�'|�)|�	w,�R4�����F�8��ň���{���:8�E����?w5�`xK�f�1��a;��n� �Ɇ�:%���T^���m�Ҿ�.��W�?�rߠ��Y��,��w:�w�Z9�����?�\.��4���O���Vai��j�*Cl^��#�j($�
�ϥyFVͲ�	}X��8*qI��$�Ϫ�0˩7��r���o�6x�dj�<��dKU��J�g�fVi3���Zu)Bod�~�gO�J#�;��o��ya�$ ��	G�|}[��񬑝�WdJ�HO�À�TJk�w��"oŸU�^��S�6<����0Y,pEf�� ͈Un�H����w_��EB7y(�(J�*�e�Le&�X.��H���Q�%&h�H����Bb�2Q-Rg߸���s�<_/�d�|����{J2?zI�6rUX ��L��o.�ދ?	�Z��B��<G٤������soY�,UI Meͪ2����xB"-=/��:E_E��;���.0>���K}���y����O�����Ɂ�׳�����(���41hf��p���n���7�kF\$r��k+j���?��clޒ�T�=x��\�o�`I1��H&T�r{E����4M�2d �?@�)��dUSMY�Fso2��z!���`ڥ���������rT<:��C��>h �n��a�X�RwL�뭽&X����	�]�s�m�ۚ�Jmp���(��Co*�fX���K���Խ�a�e���ٚ�89)�� �>g��t8˦ Y
�.�M���|��^�G����.<q�ˀ��%p���u�s��JZ]��=��	�#��h�qp���4�$͐^�	��c��m ~"���ٯ���W��#��R	�
]Y
G���6)��O���DJ�Vw�*�O<�x�/�q��T�������D�ʄǕO$ӏ˅���l�?˴A=���2<Y�D��9?Gd�S
�K�?�8�1�W�Q���W���Ђg\�(��&�J��UvED|?@\'4�ԳM$F���܍;a�S���c\ײ��S5K#��bTvb�����Fu�x��б�"�2��T�k���Eڨvk�1m<� 	�����r�����T�~�[���@h}����~��Y�{�[v���bq�]��D�N:d�G5JB�)�Lb o�MI��LKU���l�8f�U[13�e�P�F�y^yӝS�V��?��*m�&�TY3ٜ>�F�ɯ�1��7�o�&���0����g+d|�h��N0T,�#���s�a��OB��V�A��w;���s$�]Q�Wi��ga���A����$�7�@)�,OxE�y��6G�n�[/]x�nY��o�C�.0 �n�����c��(�DZ�Pp��'T8Eɩ����ǶiIb��\��
V�+�[H�S ��O�r�د�Ǹ�	ݖ	�i|�-��[S�u��vd�%B��ʧFj'������#?�����AΙ�i�6lAUI�Y�؎� �F4��Z���)UC����d�"ߖ[�U�m��B�fk}���D{�v��+�M�l�5۳^��/�
��Gh�j6�î�Ȋ�@�����0n3��&�<<�v   ÖB55��#=�$'?�LǺB��4,��k�Z�N���gt�ޥ� 	��n�ǜ�����HZ�'����R���k^���-8K��a� �~���k��n�.e_�M��@׸Y=p�y)���4 ��#�>��Ş ��y��bSS���48p�;�?ܹލ��<��tȵ�n���u�z_�>���	g��~
?ڤm��������#��.��Q�����B[6�-�����,��Yz�V�1D�T���\񌌃�!R���-ME���A�=K��<���/ (�A��Y7pWT���4�/w%�[,�^�0�8 9L�9ݞt��w�@t�a�_�ү��f�,���e�[{�ȗ�A�,����(�0�0�o!U�0�N������\/���I^I��_�/.��+�zϵM�^Hzp��o��lT�$C녝K*ɵ�K�E�)�ٟH��;��Z$_Hm|8�N%�˔��_�b��p��Řd,�&��u6��Vp���ؐJi-��:�������{�4��#ߪ��_g����Ac1"��ٽ@&�|<A3y:�{�oO� ĠVe��B����ϧ�6w��3���j$�R߃�a�m���}X��Q�өQU	��*%�������LPwM%�4�p���׭x�<R��ov�i����%y��KԬ���@J԰��/����&�P|����_V��jo�|"I�U��O��n1]%8�%��<|��,܈5���7���Tϵ3����+�bq&\3��Z�8��TH���s������\+[p����ζ�keO_�s�r��>���s��TM}T����A=�&\E��y�׵����^��rHϠqI��l���{:؃�e�~U�������P��"/u�R�o#nф�o�Fy�2i��D��Ot�����3f�j���$����j��~�eր&��Pqq����#��W(�4����9o��f<�����<�K��c�76��/t�&�/�Ns����o�9G�L�4�G��xs,���(S�!Z���LN�O���tq��hV�R�A�6vQ#v����}��q�C .�QJ��V)�W���od^]Q�c+Ȩ8j�ԟ��Q���5�ú�6"Y��]<\o�7q�a�k�N;,>�����.�:�5ߤQ�:��%��T�^]� `g;���m>�59m��*@^���>d�C���9�\S���O
�?Ι)��RTKǴ��S��pj"OT��I�<u�`�/���a����O�\����:���y����%�m�#*��k���x́գO�r��Ui���0�6�9L	3;���]�4^��}˷�	�M��a�~��?�g6��\P�^?i�E��Z��ҥ��}���_�*�kL�q �(O՗�Hq_A�j���Az��P�?/B� ��M'��2�4g�$�rM���Wd���]u��<nJ::�w͠�U�'� ��d7�Iby'��-��/���KB� {`�l�i�f�}����"ά��X�\>)����Knj�ۨ0��5��h߯��x����?��m�t���Wɨ��X����X����>��9��Ivo�X�jӏS��y@z�/�wp�<��;�.�,Wח�RQ���Iܥ=2�<>j�)k
�SA��.���b��J�&>���Yϔ��͜�j�ْÍ+��7ъ!w?���8>�2�A�b��SqC?�F��������<W��PM.���\�$2n��j Z��H�5���z2�u"�	���l*�l�K��g���|P�h�*��jT�h����އ����U,B���u�MIO��q����z?��U^.���Pˮַ� �2��^�{~��s��d�"�w��հWM��T㛔����^?k��~M�O3"`��uD�D��*Uo��ux>�d��>/&V� �<���Sv6n�X��ȇ��F�i���U��>^9�־鯾mM5Lf12��ҀH�[23l���ڔ�Yh�2k߂���ŤF����v��Ny�^�pV�̜����O�#���n�9��w�����Ѫ�\�5yځ��Z>����$7��'��'Il���^���&ƍ��38����V�����-���(�L1�b��0��*3���N�c4��ELQA�#�.p�e�M���i�GΦk��'��1�Ǟo�D(��U0	�hD�E�:��8W���M��P����F�S}�*�'�:�bҜ�j���63uM!h#�6��=�y<D���ߦ�g��3_�|D��Y7�t)O?l��U�.�b.f�8G�4N�+e�(|�x�_(�UD3ϔ�fw2�}q
O�"H��S��(N���U�|[�� TUi$��|D/rȩ�V�
�n�܅
���$���-�������.X�V=AT�$�h�8�t�r�Z�<S�8FN�
��͘�Q���k��%�6>v��E%EE�ɻVON���h���l����^r�ƚ�.0��?n�u�|�~����G��qJ�
H�~�b��GǲJ�(��>~�e_�0g�;t��m�_*(2��[Si�H<�-��R�~|*5�S�p/iۗ������2�������bZU�`�z*H曁K�QȪ)K?� b���$�<15������B[���u�59V3i{���z�@R��[;�����C��+>��#B�an:�;�âfRX�
�+�fu�V��i%�'E�8��=-c�S����@�)�B��'~N���|���U�ڽWy��>5R��C��:~&�*ܷ.S�05*`?�0�/�C�Y8G���@D�ߤ��3��7��ś������Quݒ-K>}>����'���ȖJ�$���M�Y���t���&�ød�B��ࢷ��^�����X����ᕔ6�y[F?��83��y+�U8Q��)D��S���M������;9��&�
�LedO��0Ry�v�{>I~J�G?��f��\�P}g���j󥾀����c�fK��K7��g��\���SI���OՁ�l*��2T�v�m��j�h�̼�@H���mHy�	^���*�U�<v�"��B�sҩċᬣĳ���v�.��;s������V�Z�#<�e������k�&s��}��<���L�������b|�X�l>~P*)����g�+(vYi�9��{��ѵ>�&�:�z��h"6��d,�WGyy�� �,��'�����c�vC�o:�=��kNo�tL��J;;��J\�Z���6�wi�M�<�V9�v�{N�xVѠ�#e�W�?�ͮ~�̵[X}R������	��b�W�|G�e���A](^W%VBzU��+w�4����a��������N�      �   �  x�mX[s�H}��J����.y8�H�D�B^	lb������Kv�U���t�s�{t�IOJ����2�b?�o=�g��m:��ku���p*6���F��h����o{�X��Q���q��}�Ǌ��S�|)���za>{���}���b�{*.��(���aZ�x��V�t���,���<ń���42F���v*��ҫdp��m���FUU��u��Y�z�n��a3��+ce`m�|#��!��)R�D�%[&��K��LB)��6��<>��x8k�~(&?f����T���'��/Ĥ��oC����iڷ�i���y5�?��b���w���$����@k�0TZ��$ae�M��?�W������%٣#����:Y��4�쭲�^ǻR���@�aZW�����������!�[���"�5�y��k%p��[��H��f/���G%E�o�K5��!i�n��a�[}�k���:�dF&�e@�o�����:�?��`�Q��[�
e��m�ͪ���i|Z�FR�-�N�FFY	wGX�j��/���n5����w�~�T����%a$p_Q��6j�⭆��ZQ�l�ͳ�!�Ǘ"���å.�e�sPb����
&~Y����a�շC����5��qj;�1p������Q����-��Y���̨Z���@�%ƈ<׫=���ϧ�R+i�Ծ"g�������v�8?m���c��B���s�8E�ah��e�r[|���jL��6UQ�+BV IJ.9�:��Wn�82����A,�`�?��@L^��v�����3k�����S��n��&C�u֒D�q8k��s%��&��y@N�4���K�Ύ%��L��|3ih�l^5�������-�N��G�Wˆ���W�k >đ �:w?*�!�����:=V�X|���i��]]l����])�*6ٴ^%����r��0N����B�uzs��{Ze�>_6I|r���L���C���.� y�Ii,��E ��B�
��4�9�5"e��Eb`�'���J�qd%�d EV��ĊE�w�*�5�*AB��8�O_d��r(�i����T�od�Y:o���)��.��t`7
_���
��|Mĉ�7��ŷ(L� �gB\��\��K,&��P��}�L�rI=e�a~8��\If�H�Pb?"Y��{.�-z6Ok��GB��[[�R(�k��"3"�e^�d�a���YS"��/��L&0�.���2��\�%���"t��Ds�&R!a:측���fBc:V�81�c���)N�ĸ�6��	x��g��#�� {ؾ|j��wϴҬ�=���'�W8�i]�ߖl�}C����cz�w���m(��Q��Ia�� �M�9ʤ'&�c��yɺ�8{j
_���֦��{�W&�����|�S�u'�G��_N�Lî���8�.������O����.�#^x��;�ɾ��c~ͱ���z#~?��!k���:�����O�]�)�.�p0�G�a�ō��e��'�%����'�/�
ߐ�u���a\��:P~�<dpn;1�����
3!�'ÝC�����<�ΤDT4��S �@�N�adc�~��G�2�B�����@�)�q�gr�,ݼ�F+k0?�E`�2%́�p�X����JY(�u�թw68cy|o�b�{*����tX��N �^c�4}����!.�"?�n*
�j��^�hHT.on�~!�	�W�|��"9[9�JF/�"�LC0�6�I��w�t-Q�A�����Uɔ��:�߰,�^Z����\��3��G�j�c�R�% E�ٯ<�mNmˢ��who�ʶ�ŘC��5�?Ο�@!��hHy�P�W�޹�m��9���ʭ-¸-�Ӛ��2����&g�.����% ji�שܢ$
c����:R� ������֜^��J�Zi�0�PQ�C'.�}@���"j���\\mެ@U�ȂBPa�5	.;�����Q�a|",�݅�+T�"x����U��<{(�%E���l��3L�>��u9��p���\��
9��s{�.��|2}w�=6����0{��������s���ҹQ�a���	�(;�<���Vآ��6
D �2a4��x�A��g(����P�(��}�Y�A�ՎX���}ý3N͈�s�@����{#[�7�"�86h�{z-$F��ُ�r���
�M�'�{��Mi�����>���F1#�1Ye�eR$T�1��^W�IR.�W�`W��[e��s8�-ߤe���k�L����[n���V�kN���a�LbSf.��3�I�ڍ�N��}�5`@��j�Ǉ�ŵ	�g��<�WT����k7�u��5�y'���t���1���J82X^_TA�0<%6jP��=ѓ6o���m��EB�·LM@e�GYE���Kri�E�Q]V���)K*� �p���ݸHy]�ڽ�,�0�"�Ѽʀ1�4A`0��^l�2�r�|�~�_6zy$���u��H[�r�o���j����<�B�p�b�54[���wi8(��Yhd�r]+�2q,A�d�Z������� �t��m	CI��T��;;?pR�����Cu��{�m:&����J���Y�/N�W5k���	m�g�����U_�\���R�Ӛ�b(��1��@�>�̒T���`١��A_�\%�V�oY���Rj[���m��q�E�u��8�Rr)P&���RgIf?��F� �Z*`
���Y�G��k`�[WW���ip�v���.��n`)�q$T�*�;�+;�>� o������w\�%'~^��xN�}WA�b@����A�ζoP@�v ��|�W(����yJn׏%W�T�ʏ�Ƌk3�������P��v��ئkc*�'�3�� ܾ����BN "�ьZ��2���G*���WE�4k���M�&�p�}=��Tp��D���)�<���^H�$6 �mWqB�.NL����m�A�+�BB���	�刻ϠR�u������z��'t��%1��]=�����}+M�Wu���srCr}i� 
��IƐ�Z"��������p��(5�K?䌆�� ��7q�P�틏+�>z�o��l��T�1��s�8�ѯ�`�ޑ�M���k���7l���4()�w��C&^D7��=���SG����y�s������DBX�k�{|4�!W.�����3�W��h~���z�0�".�qjE�����mõ;���7�ܚ�a]�����V������/�      �   J  x�5SɍG{�c�>rq�q�b���`�VQ�Heu~��|c�>��/�ՊOm'����%>�l������U�Uu ���Y刺���H�r��_�Of���)�f���ħ_���~�˚k
��v�ۍCT�o4�D ����ȹ/��-Ԑrݎ��s�"�Ж��$�%��n�E$���![e)��&6�Hl��<�Jl�ע:Nb�m�c=zz��Kǃ�^msJ*Ĩ��\��n�~�~�����h���3����R�PtB4����iP���}�<nv:`s���3���Չ�כ=0����h	��KG,�{3a�����D�8�(f"И1��`��I?�a���_/Pz	>$bΞ�B���l>Ǽ��������3v� �0�ʻ��:��\I���,��jJ=#c</f�P�C�ϰCF������I�`���_#�e/�m���Pr��d��p䆇!�sK,�զ��48��h��/�9�p�-PR%wA'���|��.��)�!��z�i�PnN�/Ԭz���1P^��	���&���:3g����P_���Lv�
�������������     