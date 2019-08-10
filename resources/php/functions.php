<?php

const ISO_3_TO_2 = array(
	"BGD" => "BD", "BEL" => "BE", "BFA" => "BF", "BGR" => "BG", "BIH" => "BA", "BRB" => "BB", "WLF" => "WF", "BLM" => "BL", "BMU" => "BM",
	"BRN" => "BN", "BOL" => "BO", "BHR" => "BH", "BDI" => "BI", "BEN" => "BJ", "BTN" => "BT", "JAM" => "JM", "BVT" => "BV", "BWA" => "BW",
	"WSM" => "WS", "BES" => "BQ", "BRA" => "BR", "BHS" => "BS", "JEY" => "JE", "BLR" => "BY", "BLZ" => "BZ", "RUS" => "RU", "RWA" => "RW",
	"SRB" => "RS", "TLS" => "TL", "REU" => "RE", "TKM" => "TM", "TJK" => "TJ", "ROU" => "RO", "TKL" => "TK", "GNB" => "GW", "GUM" => "GU",
	"GTM" => "GT", "SGS" => "GS", "GRC" => "GR", "GNQ" => "GQ", "GLP" => "GP", "JPN" => "JP", "GUY" => "GY", "GGY" => "GG", "GUF" => "GF",
	"GEO" => "GE", "GRD" => "GD", "GBR" => "GB", "GAB" => "GA", "SLV" => "SV", "GIN" => "GN", "GMB" => "GM", "GRL" => "GL", "GIB" => "GI",
	"GHA" => "GH", "OMN" => "OM", "TUN" => "TN", "JOR" => "JO", "HRV" => "HR", "HTI" => "HT", "HUN" => "HU", "HKG" => "HK", "HND" => "HN",
	"HMD" => "HM", "VEN" => "VE", "PRI" => "PR", "PSE" => "PS", "PLW" => "PW", "PRT" => "PT", "SJM" => "SJ", "PRY" => "PY", "IRQ" => "IQ",
	"PAN" => "PA", "PYF" => "PF", "PNG" => "PG", "PER" => "PE", "PAK" => "PK", "PHL" => "PH", "PCN" => "PN", "POL" => "PL", "SPM" => "PM",
	"ZMB" => "ZM", "ESH" => "EH", "EST" => "EE", "EGY" => "EG", "ZAF" => "ZA", "ECU" => "EC", "ITA" => "IT", "VNM" => "VN", "SLB" => "SB",
	"ETH" => "ET", "SOM" => "SO", "ZWE" => "ZW", "SAU" => "SA", "ESP" => "ES", "ERI" => "ER", "MNE" => "ME", "MDA" => "MD", "MDG" => "MG",
	"MAF" => "MF", "MAR" => "MA", "MCO" => "MC", "UZB" => "UZ", "MMR" => "MM", "MLI" => "ML", "MAC" => "MO", "MNG" => "MN", "MHL" => "MH",
	"MKD" => "MK", "MUS" => "MU", "MLT" => "MT", "MWI" => "MW", "MDV" => "MV", "MTQ" => "MQ", "MNP" => "MP", "MSR" => "MS", "MRT" => "MR",
	"IMN" => "IM", "UGA" => "UG", "TZA" => "TZ", "MYS" => "MY", "MEX" => "MX", "ISR" => "IL", "FRA" => "FR", "IOT" => "IO", "SHN" => "SH",
	"FIN" => "FI", "FJI" => "FJ", "FLK" => "FK", "FSM" => "FM", "FRO" => "FO", "NIC" => "NI", "NLD" => "NL", "NOR" => "NO", "NAM" => "NA",
	"VUT" => "VU", "NCL" => "NC", "NER" => "NE", "NFK" => "NF", "NGA" => "NG", "NZL" => "NZ", "NPL" => "NP", "NRU" => "NR", "NIU" => "NU",
	"COK" => "CK", "XKX" => "XK", "CIV" => "CI", "CHE" => "CH", "COL" => "CO", "CHN" => "CN", "CMR" => "CM", "CHL" => "CL", "CCK" => "CC",
	"CAN" => "CA", "COG" => "CG", "CAF" => "CF", "COD" => "CD", "CZE" => "CZ", "CYP" => "CY", "CXR" => "CX", "CRI" => "CR", "CUW" => "CW",
	"CPV" => "CV", "CUB" => "CU", "SWZ" => "SZ", "SYR" => "SY", "SXM" => "SX", "KGZ" => "KG", "KEN" => "KE", "SSD" => "SS", "SUR" => "SR",
	"KIR" => "KI", "KHM" => "KH", "KNA" => "KN", "COM" => "KM", "STP" => "ST", "SVK" => "SK", "KOR" => "KR", "SVN" => "SI", "PRK" => "KP",
	"KWT" => "KW", "SEN" => "SN", "SMR" => "SM", "SLE" => "SL", "SYC" => "SC", "KAZ" => "KZ", "CYM" => "KY", "SGP" => "SG", "SWE" => "SE",
	"SDN" => "SD", "DOM" => "DO", "DMA" => "DM", "DJI" => "DJ", "DNK" => "DK", "VGB" => "VG", "DEU" => "DE", "YEM" => "YE", "DZA" => "DZ",
	"USA" => "US", "URY" => "UY", "MYT" => "YT", "UMI" => "UM", "LBN" => "LB", "LCA" => "LC", "LAO" => "LA", "TUV" => "TV", "TWN" => "TW",
	"TTO" => "TT", "TUR" => "TR", "LKA" => "LK", "LIE" => "LI", "LVA" => "LV", "TON" => "TO", "LTU" => "LT", "LUX" => "LU", "LBR" => "LR",
	"LSO" => "LS", "THA" => "TH", "ATF" => "TF", "TGO" => "TG", "TCD" => "TD", "TCA" => "TC", "LBY" => "LY", "VAT" => "VA", "VCT" => "VC",
	"ARE" => "AE", "AND" => "AD", "ATG" => "AG", "AFG" => "AF", "AIA" => "AI", "VIR" => "VI", "ISL" => "IS", "IRN" => "IR", "ARM" => "AM",
	"ALB" => "AL", "AGO" => "AO", "ATA" => "AQ", "ASM" => "AS", "ARG" => "AR", "AUS" => "AU", "AUT" => "AT", "ABW" => "AW", "IND" => "IN",
	"ALA" => "AX", "AZE" => "AZ", "IRL" => "IE", "IDN" => "ID", "UKR" => "UA", "QAT" => "QA", "MOZ" => "MZ"
);

const POKEMON_ID_TO_NAME = array(
	"1" => "Bulbasaur",
	"2" => "Ivysaur",
	"3" => "Venusaur",
	"4" => "Charmander",
	"5" => "Charmeleon",
	"6" => "Charizard",
	"7" => "Squirtle",
	"8" => "Wartortle",
	"9" => "Blastoise",
	"10" => "Caterpie",
	"11" => "Metapod",
	"12" => "Butterfree",
	"13" => "Weedle",
	"14" => "Kakuna",
	"15" => "Beedrill",
	"16" => "Pidgey",
	"17" => "Pidgeotto",
	"18" => "Pidgeot",
	"19" => "Rattata",
	"20" => "Raticate",
	"21" => "Spearow",
	"22" => "Fearow",
	"23" => "Ekans",
	"24" => "Arbok",
	"25" => "Pikachu",
	"26" => "Raichu",
	"27" => "Sandshrew",
	"28" => "Sandslash",
	"29" => "Nidoran-F",
	"30" => "Nidorina",
	"31" => "Nidoqueen",
	"32" => "Nidoran-M",
	"33" => "Nidorino",
	"34" => "Nidoking",
	"35" => "Clefairy",
	"36" => "Clefable",
	"37" => "Vulpix",
	"38" => "Ninetales",
	"39" => "Jigglypuff",
	"40" => "Wigglytuff",
	"41" => "Zubat",
	"42" => "Golbat",
	"43" => "Oddish",
	"44" => "Gloom",
	"45" => "Vileplume",
	"46" => "Paras",
	"47" => "Parasect",
	"48" => "Venonat",
	"49" => "Venomoth",
	"50" => "Diglett",
	"51" => "Dugtrio",
	"52" => "Meowth",
	"53" => "Persian",
	"54" => "Psyduck",
	"55" => "Golduck",
	"56" => "Mankey",
	"57" => "Primeape",
	"58" => "Growlithe",
	"59" => "Arcanine",
	"60" => "Poliwag",
	"61" => "Poliwhirl",
	"62" => "Poliwrath",
	"63" => "Abra",
	"64" => "Kadabra",
	"65" => "Alakazam",
	"66" => "Machop",
	"67" => "Machoke",
	"68" => "Machamp",
	"69" => "Bellsprout",
	"70" => "Weepinbell",
	"71" => "Victreebel",
	"72" => "Tentacool",
	"73" => "Tentacruel",
	"74" => "Geodude",
	"75" => "Graveler",
	"76" => "Golem",
	"77" => "Ponyta",
	"78" => "Rapidash",
	"79" => "Slowpoke",
	"80" => "Slowbro",
	"81" => "Magnemite",
	"82" => "Magneton",
	"83" => "Farfetch’d",
	"84" => "Doduo",
	"85" => "Dodrio",
	"86" => "Seel",
	"87" => "Dewgong",
	"88" => "Grimer",
	"89" => "Muk",
	"90" => "Shellder",
	"91" => "Cloyster",
	"92" => "Gastly",
	"93" => "Haunter",
	"94" => "Gengar",
	"95" => "Onix",
	"96" => "Drowzee",
	"97" => "Hypno",
	"98" => "Krabby",
	"99" => "Kingler",
	"100" => "Voltorb",
	"101" => "Electrode",
	"102" => "Exeggcute",
	"103" => "Exeggutor",
	"104" => "Cubone",
	"105" => "Marowak",
	"106" => "Hitmonlee",
	"107" => "Hitmonchan",
	"108" => "Lickitung",
	"109" => "Koffing",
	"110" => "Weezing",
	"111" => "Rhyhorn",
	"112" => "Rhydon",
	"113" => "Chansey",
	"114" => "Tangela",
	"115" => "Kangaskhan",
	"116" => "Horsea",
	"117" => "Seadra",
	"118" => "Goldeen",
	"119" => "Seaking",
	"120" => "Staryu",
	"121" => "Starmie",
	"122" => "Mr. Mime",
	"123" => "Scyther",
	"124" => "Jynx",
	"125" => "Electabuzz",
	"126" => "Magmar",
	"127" => "Pinsir",
	"128" => "Tauros",
	"129" => "Magikarp",
	"130" => "Gyarados",
	"131" => "Lapras",
	"132" => "Ditto",
	"133" => "Eevee",
	"134" => "Vaporeon",
	"135" => "Jolteon",
	"136" => "Flareon",
	"137" => "Porygon",
	"138" => "Omanyte",
	"139" => "Omastar",
	"140" => "Kabuto",
	"141" => "Kabutops",
	"142" => "Aerodactyl",
	"143" => "Snorlax",
	"144" => "Articuno",
	"145" => "Zapdos",
	"146" => "Moltres",
	"147" => "Dratini",
	"148" => "Dragonair",
	"149" => "Dragonite",
	"150" => "Mewtwo",
	"151" => "Mew",
	"152" => "Chikorita",
	"153" => "Bayleef",
	"154" => "Meganium",
	"155" => "Cyndaquil",
	"156" => "Quilava",
	"157" => "Typhlosion",
	"158" => "Totodile",
	"159" => "Croconaw",
	"160" => "Feraligatr",
	"161" => "Sentret",
	"162" => "Furret",
	"163" => "Hoothoot",
	"164" => "Noctowl",
	"165" => "Ledyba",
	"166" => "Ledian",
	"167" => "Spinarak",
	"168" => "Ariados",
	"169" => "Crobat",
	"170" => "Chinchou",
	"171" => "Lanturn",
	"172" => "Pichu",
	"173" => "Cleffa",
	"174" => "Igglybuff",
	"175" => "Togepi",
	"176" => "Togetic",
	"177" => "Natu",
	"178" => "Xatu",
	"179" => "Mareep",
	"180" => "Flaaffy",
	"181" => "Ampharos",
	"182" => "Bellossom",
	"183" => "Marill",
	"184" => "Azumarill",
	"185" => "Sudowoodo",
	"186" => "Politoed",
	"187" => "Hoppip",
	"188" => "Skiploom",
	"189" => "Jumpluff",
	"190" => "Aipom",
	"191" => "Sunkern",
	"192" => "Sunflora",
	"193" => "Yanma",
	"194" => "Wooper",
	"195" => "Quagsire",
	"196" => "Espeon",
	"197" => "Umbreon",
	"198" => "Murkrow",
	"199" => "Slowking",
	"200" => "Misdreavus",
	"201" => "Unown",
	"202" => "Wobbuffet",
	"203" => "Girafarig",
	"204" => "Pineco",
	"205" => "Forretress",
	"206" => "Dunsparce",
	"207" => "Gligar",
	"208" => "Steelix",
	"209" => "Snubbull",
	"210" => "Granbull",
	"211" => "Qwilfish",
	"212" => "Scizor",
	"213" => "Shuckle",
	"214" => "Heracross",
	"215" => "Sneasel",
	"216" => "Teddiursa",
	"217" => "Ursaring",
	"218" => "Slugma",
	"219" => "Magcargo",
	"220" => "Swinub",
	"221" => "Piloswine",
	"222" => "Corsola",
	"223" => "Remoraid",
	"224" => "Octillery",
	"225" => "Delibird",
	"226" => "Mantine",
	"227" => "Skarmory",
	"228" => "Houndour",
	"229" => "Houndoom",
	"230" => "Kingdra",
	"231" => "Phanpy",
	"232" => "Donphan",
	"233" => "Porygon2",
	"234" => "Stantler",
	"235" => "Smeargle",
	"236" => "Tyrogue",
	"237" => "Hitmontop",
	"238" => "Smoochum",
	"239" => "Elekid",
	"240" => "Magby",
	"241" => "Miltank",
	"242" => "Blissey",
	"243" => "Raikou",
	"244" => "Entei",
	"245" => "Suicune",
	"246" => "Larvitar",
	"247" => "Pupitar",
	"248" => "Tyranitar",
	"249" => "Lugia",
	"250" => "Ho-Oh",
	"251" => "Celebi",
	"252" => "Treecko",
	"253" => "Grovyle",
	"254" => "Sceptile",
	"255" => "Torchic",
	"256" => "Combusken",
	"257" => "Blaziken",
	"258" => "Mudkip",
	"259" => "Marshtomp",
	"260" => "Swampert",
	"261" => "Poochyena",
	"262" => "Mightyena",
	"263" => "Zigzagoon",
	"264" => "Linoone",
	"265" => "Wurmple",
	"266" => "Silcoon",
	"267" => "Beautifly",
	"268" => "Cascoon",
	"269" => "Dustox",
	"270" => "Lotad",
	"271" => "Lombre",
	"272" => "Ludicolo",
	"273" => "Seedot",
	"274" => "Nuzleaf",
	"275" => "Shiftry",
	"276" => "Taillow",
	"277" => "Swellow",
	"278" => "Wingull",
	"279" => "Pelipper",
	"280" => "Ralts",
	"281" => "Kirlia",
	"282" => "Gardevoir",
	"283" => "Surskit",
	"284" => "Masquerain",
	"285" => "Shroomish",
	"286" => "Breloom",
	"287" => "Slakoth",
	"288" => "Vigoroth",
	"289" => "Slaking",
	"290" => "Nincada",
	"291" => "Ninjask",
	"292" => "Shedinja",
	"293" => "Whismur",
	"294" => "Loudred",
	"295" => "Exploud",
	"296" => "Makuhita",
	"297" => "Hariyama",
	"298" => "Azurill",
	"299" => "Nosepass",
	"300" => "Skitty",
	"301" => "Delcatty",
	"302" => "Sableye",
	"303" => "Mawile",
	"304" => "Aron",
	"305" => "Lairon",
	"306" => "Aggron",
	"307" => "Meditite",
	"308" => "Medicham",
	"309" => "Electrike",
	"310" => "Manectric",
	"311" => "Plusle",
	"312" => "Minun",
	"313" => "Volbeat",
	"314" => "Illumise",
	"315" => "Roselia",
	"316" => "Gulpin",
	"317" => "Swalot",
	"318" => "Carvanha",
	"319" => "Sharpedo",
	"320" => "Wailmer",
	"321" => "Wailord",
	"322" => "Numel",
	"323" => "Camerupt",
	"324" => "Torkoal",
	"325" => "Spoink",
	"326" => "Grumpig",
	"327" => "Spinda",
	"328" => "Trapinch",
	"329" => "Vibrava",
	"330" => "Flygon",
	"331" => "Cacnea",
	"332" => "Cacturne",
	"333" => "Swablu",
	"334" => "Altaria",
	"335" => "Zangoose",
	"336" => "Seviper",
	"337" => "Lunatone",
	"338" => "Solrock",
	"339" => "Barboach",
	"340" => "Whiscash",
	"341" => "Corphish",
	"342" => "Crawdaunt",
	"343" => "Baltoy",
	"344" => "Claydol",
	"345" => "Lileep",
	"346" => "Cradily",
	"347" => "Anorith",
	"348" => "Armaldo",
	"349" => "Feebas",
	"350" => "Milotic",
	"351" => "Castform",
	"352" => "Kecleon",
	"353" => "Shuppet",
	"354" => "Banette",
	"355" => "Duskull",
	"356" => "Dusclops",
	"357" => "Tropius",
	"358" => "Chimecho",
	"359" => "Absol",
	"360" => "Wynaut",
	"361" => "Snorunt",
	"362" => "Glalie",
	"363" => "Spheal",
	"364" => "Sealeo",
	"365" => "Walrein",
	"366" => "Clamperl",
	"367" => "Huntail",
	"368" => "Gorebyss",
	"369" => "Relicanth",
	"370" => "Luvdisc",
	"371" => "Bagon",
	"372" => "Shelgon",
	"373" => "Salamence",
	"374" => "Beldum",
	"375" => "Metang",
	"376" => "Metagross",
	"377" => "Regirock",
	"378" => "Regice",
	"379" => "Registeel",
	"380" => "Latias",
	"381" => "Latios",
	"382" => "Kyogre",
	"383" => "Groudon",
	"384" => "Rayquaza",
	"385" => "Jirachi",
	"386" => "Deoxys",
	"387" => "Turtwig",
	"388" => "Grotle",
	"389" => "Torterra",
	"390" => "Chimchar",
	"391" => "Monferno",
	"392" => "Infernape",
	"393" => "Piplup",
	"394" => "Prinplup",
	"395" => "Empoleon",
	"396" => "Starly",
	"397" => "Staravia",
	"398" => "Staraptor",
	"399" => "Bidoof",
	"400" => "Bibarel",
	"401" => "Kricketot",
	"402" => "Kricketune",
	"403" => "Shinx",
	"404" => "Luxio",
	"405" => "Luxray",
	"406" => "Budew",
	"407" => "Roserade",
	"408" => "Cranidos",
	"409" => "Rampardos",
	"410" => "Shieldon",
	"411" => "Bastiodon",
	"412" => "Burmy",
	"413" => "Wormadam",
	"414" => "Mothim",
	"415" => "Combee",
	"416" => "Vespiquen",
	"417" => "Pachirisu",
	"418" => "Buizel",
	"419" => "Floatzel",
	"420" => "Cherubi",
	"421" => "Cherrim",
	"422" => "Shellos",
	"423" => "Gastrodon",
	"424" => "Ambipom",
	"425" => "Drifloon",
	"426" => "Drifblim",
	"427" => "Buneary",
	"428" => "Lopunny",
	"429" => "Mismagius",
	"430" => "Honchkrow",
	"431" => "Glameow",
	"432" => "Purugly",
	"433" => "Chingling",
	"434" => "Stunky",
	"435" => "Skuntank",
	"436" => "Bronzor",
	"437" => "Bronzong",
	"438" => "Bonsly",
	"439" => "Mime Jr.",
	"440" => "Happiny",
	"441" => "Chatot",
	"442" => "Spiritomb",
	"443" => "Gible",
	"444" => "Gabite",
	"445" => "Garchomp",
	"446" => "Munchlax",
	"447" => "Riolu",
	"448" => "Lucario",
	"449" => "Hippopotas",
	"450" => "Hippowdon",
	"451" => "Skorupi",
	"452" => "Drapion",
	"453" => "Croagunk",
	"454" => "Toxicroak",
	"455" => "Carnivine",
	"456" => "Finneon",
	"457" => "Lumineon",
	"458" => "Mantyke",
	"459" => "Snover",
	"460" => "Abomasnow",
	"461" => "Weavile",
	"462" => "Magnezone",
	"463" => "Lickilicky",
	"464" => "Rhyperior",
	"465" => "Tangrowth",
	"466" => "Electivire",
	"467" => "Magmortar",
	"468" => "Togekiss",
	"469" => "Yanmega",
	"470" => "Leafeon",
	"471" => "Glaceon",
	"472" => "Gliscor",
	"473" => "Mamoswine",
	"474" => "Porygon-Z",
	"475" => "Gallade",
	"476" => "Probopass",
	"477" => "Dusknoir",
	"478" => "Froslass",
	"479" => "Rotom",
	"480" => "Uxie",
	"481" => "Mesprit",
	"482" => "Azelf",
	"483" => "Dialga",
	"484" => "Palkia",
	"485" => "Heatran",
	"486" => "Regigigas",
	"487" => "Giratina",
	"488" => "Cresselia",
	"489" => "Phione",
	"490" => "Manaphy",
	"491" => "Darkrai",
	"492" => "Shaymin",
	"493" => "Arceus",
	"494" => "Victini",
	"495" => "Snivy",
	"496" => "Servine",
	"497" => "Serperior",
	"498" => "Tepig",
	"499" => "Pignite",
	"500" => "Emboar",
	"501" => "Oshawott",
	"502" => "Dewott",
	"503" => "Samurott",
	"504" => "Patrat",
	"505" => "Watchog",
	"506" => "Lillipup",
	"507" => "Herdier",
	"508" => "Stoutland",
	"509" => "Purrloin",
	"510" => "Liepard",
	"511" => "Pansage",
	"512" => "Simisage",
	"513" => "Pansear",
	"514" => "Simisear",
	"515" => "Panpour",
	"516" => "Simipour",
	"517" => "Munna",
	"518" => "Musharna",
	"519" => "Pidove",
	"520" => "Tranquill",
	"521" => "Unfezant",
	"522" => "Blitzle",
	"523" => "Zebstrika",
	"524" => "Roggenrola",
	"525" => "Boldore",
	"526" => "Gigalith",
	"527" => "Woobat",
	"528" => "Swoobat",
	"529" => "Drilbur",
	"530" => "Excadrill",
	"531" => "Audino",
	"532" => "Timburr",
	"533" => "Gurdurr",
	"534" => "Conkeldurr",
	"535" => "Tympole",
	"536" => "Palpitoad",
	"537" => "Seismitoad",
	"538" => "Throh",
	"539" => "Sawk",
	"540" => "Sewaddle",
	"541" => "Swadloon",
	"542" => "Leavanny",
	"543" => "Venipede",
	"544" => "Whirlipede",
	"545" => "Scolipede",
	"546" => "Cottonee",
	"547" => "Whimsicott",
	"548" => "Petilil",
	"549" => "Lilligant",
	"550" => "Basculin",
	"551" => "Sandile",
	"552" => "Krokorok",
	"553" => "Krookodile",
	"554" => "Darumaka",
	"555" => "Darmanitan",
	"556" => "Maractus",
	"557" => "Dwebble",
	"558" => "Crustle",
	"559" => "Scraggy",
	"560" => "Scrafty",
	"561" => "Sigilyph",
	"562" => "Yamask",
	"563" => "Cofagrigus",
	"564" => "Tirtouga",
	"565" => "Carracosta",
	"566" => "Archen",
	"567" => "Archeops",
	"568" => "Trubbish",
	"569" => "Garbodor",
	"570" => "Zorua",
	"571" => "Zoroark",
	"572" => "Minccino",
	"573" => "Cinccino",
	"574" => "Gothita",
	"575" => "Gothorita",
	"576" => "Gothitelle",
	"577" => "Solosis",
	"578" => "Duosion",
	"579" => "Reuniclus",
	"580" => "Ducklett",
	"581" => "Swanna",
	"582" => "Vanillite",
	"583" => "Vanillish",
	"584" => "Vanilluxe",
	"585" => "Deerling",
	"586" => "Sawsbuck",
	"587" => "Emolga",
	"588" => "Karrablast",
	"589" => "Escavalier",
	"590" => "Foongus",
	"591" => "Amoonguss",
	"592" => "Frillish",
	"593" => "Jellicent",
	"594" => "Alomomola",
	"595" => "Joltik",
	"596" => "Galvantula",
	"597" => "Ferroseed",
	"598" => "Ferrothorn",
	"599" => "Klink",
	"600" => "Klang",
	"601" => "Klinklang",
	"602" => "Tynamo",
	"603" => "Eelektrik",
	"604" => "Eelektross",
	"605" => "Elgyem",
	"606" => "Beheeyem",
	"607" => "Litwick",
	"608" => "Lampent",
	"609" => "Chandelure",
	"610" => "Axew",
	"611" => "Fraxure",
	"612" => "Haxorus",
	"613" => "Cubchoo",
	"614" => "Beartic",
	"615" => "Cryogonal",
	"616" => "Shelmet",
	"617" => "Accelgor",
	"618" => "Stunfisk",
	"619" => "Mienfoo",
	"620" => "Mienshao",
	"621" => "Druddigon",
	"622" => "Golett",
	"623" => "Golurk",
	"624" => "Pawniard",
	"625" => "Bisharp",
	"626" => "Bouffalant",
	"627" => "Rufflet",
	"628" => "Braviary",
	"629" => "Vullaby",
	"630" => "Mandibuzz",
	"631" => "Heatmor",
	"632" => "Durant",
	"633" => "Deino",
	"634" => "Zweilous",
	"635" => "Hydreigon",
	"636" => "Larvesta",
	"637" => "Volcarona",
	"638" => "Cobalion",
	"639" => "Terrakion",
	"640" => "Virizion",
	"641" => "Tornadus",
	"642" => "Thundurus",
	"643" => "Reshiram",
	"644" => "Zekrom",
	"645" => "Landorus",
	"646" => "Kyurem",
	"647" => "Keldeo",
	"648" => "Meloetta",
	"649" => "Genesect",
	"650" => "Chespin",
	"651" => "Quilladin",
	"652" => "Chesnaught",
	"653" => "Fennekin",
	"654" => "Braixen",
	"655" => "Delphox",
	"656" => "Froakie",
	"657" => "Frogadier",
	"658" => "Greninja",
	"659" => "Bunnelby",
	"660" => "Diggersby",
	"661" => "Fletchling",
	"662" => "Fletchinder",
	"663" => "Talonflame",
	"664" => "Scatterbug",
	"665" => "Spewpa",
	"666" => "Vivillon",
	"667" => "Litleo",
	"668" => "Pyroar",
	"669" => "Flabébé",
	"670" => "Floette",
	"671" => "Florges",
	"672" => "Skiddo",
	"673" => "Gogoat",
	"674" => "Pancham",
	"675" => "Pangoro",
	"676" => "Furfrou",
	"677" => "Espurr",
	"678" => "Meowstic",
	"679" => "Honedge",
	"680" => "Doublade",
	"681" => "Aegislash",
	"682" => "Spritzee",
	"683" => "Aromatisse",
	"684" => "Swirlix",
	"685" => "Slurpuff",
	"686" => "Inkay",
	"687" => "Malamar",
	"688" => "Binacle",
	"689" => "Barbaracle",
	"690" => "Skrelp",
	"691" => "Dragalge",
	"692" => "Clauncher",
	"693" => "Clawitzer",
	"694" => "Helioptile",
	"695" => "Heliolisk",
	"696" => "Tyrunt",
	"697" => "Tyrantrum",
	"698" => "Amaura",
	"699" => "Aurorus",
	"700" => "Sylveon",
	"701" => "Hawlucha",
	"702" => "Dedenne",
	"703" => "Carbink",
	"704" => "Goomy",
	"705" => "Sliggoo",
	"706" => "Goodra",
	"707" => "Klefki",
	"708" => "Phantump",
	"709" => "Trevenant",
	"710" => "Pumpkaboo",
	"711" => "Gourgeist",
	"712" => "Bergmite",
	"713" => "Avalugg",
	"714" => "Noibat",
	"715" => "Noivern",
	"716" => "Xerneas",
	"717" => "Yveltal",
	"718" => "Zygarde",
	"719" => "Diancie",
	"720" => "Hoopa",
	"721" => "Volcanion",
	"722" => "Rowlet",
	"723" => "Dartrix",
	"724" => "Decidueye",
	"725" => "Litten",
	"726" => "Torracat",
	"727" => "Incineroar",
	"728" => "Popplio",
	"729" => "Brionne",
	"730" => "Primarina",
	"731" => "Pikipek",
	"732" => "Trumbeak",
	"733" => "Toucannon",
	"734" => "Yungoos",
	"735" => "Gumshoos",
	"736" => "Grubbin",
	"737" => "Charjabug",
	"738" => "Vikavolt",
	"739" => "Crabrawler",
	"740" => "Crabominable",
	"741" => "Oricorio",
	"742" => "Cutiefly",
	"743" => "Ribombee",
	"744" => "Rockruff",
	"745" => "Lycanroc",
	"746" => "Wishiwashi",
	"747" => "Mareanie",
	"748" => "Toxapex",
	"749" => "Mudbray",
	"750" => "Mudsdale",
	"751" => "Dewpider",
	"752" => "Araquanid",
	"753" => "Fomantis",
	"754" => "Lurantis",
	"755" => "Morelull",
	"756" => "Shiinotic",
	"757" => "Salandit",
	"758" => "Salazzle",
	"759" => "Stufful",
	"760" => "Bewear",
	"761" => "Bounsweet",
	"762" => "Steenee",
	"763" => "Tsareena",
	"764" => "Comfey",
	"765" => "Oranguru",
	"766" => "Passimian",
	"767" => "Wimpod",
	"768" => "Golisopod",
	"769" => "Sandygast",
	"770" => "Palossand",
	"771" => "Pyukumuku",
	"772" => "Type: Null",
	"773" => "Silvally",
	"774" => "Minior",
	"775" => "Komala",
	"776" => "Turtonator",
	"777" => "Togedemaru",
	"778" => "Mimikyu",
	"779" => "Bruxish",
	"780" => "Drampa",
	"781" => "Dhelmise",
	"782" => "Jangmo-o",
	"783" => "Hakamo-o",
	"784" => "Kommo-o",
	"785" => "Tapu Koko",
	"786" => "Tapu Lele",
	"787" => "Tapu Bulu",
	"788" => "Tapu Fini",
	"789" => "Cosmog",
	"790" => "Cosmoem",
	"791" => "Solgaleo",
	"792" => "Lunala",
	"793" => "Nihilego",
	"794" => "Buzzwole",
	"795" => "Pheromosa",
	"796" => "Xurkitree",
	"797" => "Celesteela",
	"798" => "Kartana",
	"799" => "Guzzlord",
	"800" => "Necrozma",
	"801" => "Magearna",
	"802" => "Marshadow",
	"803" => "Poipole",
	"804" => "Naganadel",
	"805" => "Stakataka",
	"806" => "Blacephalon",
	"807" => "Zeraora",
	"808" => "Meltan",
	"809" => "Melmetal"
);

const POKEMON_NAME_TO_ID = array(
	"bulbasaur" => 1,
	"ivysaur" => 2,
	"venusaur" => 3,
	"charmander" => 4,
	"charmeleon" => 5,
	"charizard" => 6,
	"squirtle" => 7,
	"wartortle" => 8,
	"blastoise" => 9,
	"caterpie" => 10,
	"metapod" => 11,
	"butterfree" => 12,
	"weedle" => 13,
	"kakuna" => 14,
	"beedrill" => 15,
	"pidgey" => 16,
	"pidgeotto" => 17,
	"pidgeot" => 18,
	"rattata" => 19,
	"raticate" => 20,
	"spearow" => 21,
	"fearow" => 22,
	"ekans" => 23,
	"arbok" => 24,
	"pikachu" => 25,
	"raichu" => 26,
	"sandshrew" => 27,
	"sandslash" => 28,
	"nidoranf" => 29,
	"nidorina" => 30,
	"nidoqueen" => 31,
	"nidoranm" => 32,
	"nidorino" => 33,
	"nidoking" => 34,
	"clefairy" => 35,
	"clefable" => 36,
	"vulpix" => 37,
	"ninetales" => 38,
	"jigglypuff" => 39,
	"wigglytuff" => 40,
	"zubat" => 41,
	"golbat" => 42,
	"oddish" => 43,
	"gloom" => 44,
	"vileplume" => 45,
	"paras" => 46,
	"parasect" => 47,
	"venonat" => 48,
	"venomoth" => 49,
	"diglett" => 50,
	"dugtrio" => 51,
	"meowth" => 52,
	"persian" => 53,
	"psyduck" => 54,
	"golduck" => 55,
	"mankey" => 56,
	"primeape" => 57,
	"growlithe" => 58,
	"arcanine" => 59,
	"poliwag" => 60,
	"poliwhirl" => 61,
	"poliwrath" => 62,
	"abra" => 63,
	"kadabra" => 64,
	"alakazam" => 65,
	"machop" => 66,
	"machoke" => 67,
	"machamp" => 68,
	"bellsprout" => 69,
	"weepinbell" => 70,
	"victreebel" => 71,
	"tentacool" => 72,
	"tentacruel" => 73,
	"geodude" => 74,
	"graveler" => 75,
	"golem" => 76,
	"ponyta" => 77,
	"rapidash" => 78,
	"slowpoke" => 79,
	"slowbro" => 80,
	"magnemite" => 81,
	"magneton" => 82,
	"farfetch’d" => 83,
	"doduo" => 84,
	"dodrio" => 85,
	"seel" => 86,
	"dewgong" => 87,
	"grimer" => 88,
	"muk" => 89,
	"shellder" => 90,
	"cloyster" => 91,
	"gastly" => 92,
	"haunter" => 93,
	"gengar" => 94,
	"onix" => 95,
	"drowzee" => 96,
	"hypno" => 97,
	"krabby" => 98,
	"kingler" => 99,
	"voltorb" => 100,
	"electrode" => 101,
	"exeggcute" => 102,
	"exeggutor" => 103,
	"cubone" => 104,
	"marowak" => 105,
	"hitmonlee" => 106,
	"hitmonchan" => 107,
	"lickitung" => 108,
	"koffing" => 109,
	"weezing" => 110,
	"rhyhorn" => 111,
	"rhydon" => 112,
	"chansey" => 113,
	"tangela" => 114,
	"kangaskhan" => 115,
	"horsea" => 116,
	"seadra" => 117,
	"goldeen" => 118,
	"seaking" => 119,
	"staryu" => 120,
	"starmie" => 121,
	"mr.mime" => 122,
	"scyther" => 123,
	"jynx" => 124,
	"electabuzz" => 125,
	"magmar" => 126,
	"pinsir" => 127,
	"tauros" => 128,
	"magikarp" => 129,
	"gyarados" => 130,
	"lapras" => 131,
	"ditto" => 132,
	"eevee" => 133,
	"vaporeon" => 134,
	"jolteon" => 135,
	"flareon" => 136,
	"porygon" => 137,
	"omanyte" => 138,
	"omastar" => 139,
	"kabuto" => 140,
	"kabutops" => 141,
	"aerodactyl" => 142,
	"snorlax" => 143,
	"articuno" => 144,
	"zapdos" => 145,
	"moltres" => 146,
	"dratini" => 147,
	"dragonair" => 148,
	"dragonite" => 149,
	"mewtwo" => 150,
	"mew" => 151,
	"chikorita" => 152,
	"bayleef" => 153,
	"meganium" => 154,
	"cyndaquil" => 155,
	"quilava" => 156,
	"typhlosion" => 157,
	"totodile" => 158,
	"croconaw" => 159,
	"feraligatr" => 160,
	"sentret" => 161,
	"furret" => 162,
	"hoothoot" => 163,
	"noctowl" => 164,
	"ledyba" => 165,
	"ledian" => 166,
	"spinarak" => 167,
	"ariados" => 168,
	"crobat" => 169,
	"chinchou" => 170,
	"lanturn" => 171,
	"pichu" => 172,
	"cleffa" => 173,
	"igglybuff" => 174,
	"togepi" => 175,
	"togetic" => 176,
	"natu" => 177,
	"xatu" => 178,
	"mareep" => 179,
	"flaaffy" => 180,
	"ampharos" => 181,
	"bellossom" => 182,
	"marill" => 183,
	"azumarill" => 184,
	"sudowoodo" => 185,
	"politoed" => 186,
	"hoppip" => 187,
	"skiploom" => 188,
	"jumpluff" => 189,
	"aipom" => 190,
	"sunkern" => 191,
	"sunflora" => 192,
	"yanma" => 193,
	"wooper" => 194,
	"quagsire" => 195,
	"espeon" => 196,
	"umbreon" => 197,
	"murkrow" => 198,
	"slowking" => 199,
	"misdreavus" => 200,
	"unown" => 201,
	"wobbuffet" => 202,
	"girafarig" => 203,
	"pineco" => 204,
	"forretress" => 205,
	"dunsparce" => 206,
	"gligar" => 207,
	"steelix" => 208,
	"snubbull" => 209,
	"granbull" => 210,
	"qwilfish" => 211,
	"scizor" => 212,
	"shuckle" => 213,
	"heracross" => 214,
	"sneasel" => 215,
	"teddiursa" => 216,
	"ursaring" => 217,
	"slugma" => 218,
	"magcargo" => 219,
	"swinub" => 220,
	"piloswine" => 221,
	"corsola" => 222,
	"remoraid" => 223,
	"octillery" => 224,
	"delibird" => 225,
	"mantine" => 226,
	"skarmory" => 227,
	"houndour" => 228,
	"houndoom" => 229,
	"kingdra" => 230,
	"phanpy" => 231,
	"donphan" => 232,
	"porygon2" => 233,
	"stantler" => 234,
	"smeargle" => 235,
	"tyrogue" => 236,
	"hitmontop" => 237,
	"smoochum" => 238,
	"elekid" => 239,
	"magby" => 240,
	"miltank" => 241,
	"blissey" => 242,
	"raikou" => 243,
	"entei" => 244,
	"suicune" => 245,
	"larvitar" => 246,
	"pupitar" => 247,
	"tyranitar" => 248,
	"lugia" => 249,
	"hooh" => 250,
	"celebi" => 251,
	"treecko" => 252,
	"grovyle" => 253,
	"sceptile" => 254,
	"torchic" => 255,
	"combusken" => 256,
	"blaziken" => 257,
	"mudkip" => 258,
	"marshtomp" => 259,
	"swampert" => 260,
	"poochyena" => 261,
	"mightyena" => 262,
	"zigzagoon" => 263,
	"linoone" => 264,
	"wurmple" => 265,
	"silcoon" => 266,
	"beautifly" => 267,
	"cascoon" => 268,
	"dustox" => 269,
	"lotad" => 270,
	"lombre" => 271,
	"ludicolo" => 272,
	"seedot" => 273,
	"nuzleaf" => 274,
	"shiftry" => 275,
	"taillow" => 276,
	"swellow" => 277,
	"wingull" => 278,
	"pelipper" => 279,
	"ralts" => 280,
	"kirlia" => 281,
	"gardevoir" => 282,
	"surskit" => 283,
	"masquerain" => 284,
	"shroomish" => 285,
	"breloom" => 286,
	"slakoth" => 287,
	"vigoroth" => 288,
	"slaking" => 289,
	"nincada" => 290,
	"ninjask" => 291,
	"shedinja" => 292,
	"whismur" => 293,
	"loudred" => 294,
	"exploud" => 295,
	"makuhita" => 296,
	"hariyama" => 297,
	"azurill" => 298,
	"nosepass" => 299,
	"skitty" => 300,
	"delcatty" => 301,
	"sableye" => 302,
	"mawile" => 303,
	"aron" => 304,
	"lairon" => 305,
	"aggron" => 306,
	"meditite" => 307,
	"medicham" => 308,
	"electrike" => 309,
	"manectric" => 310,
	"plusle" => 311,
	"minun" => 312,
	"volbeat" => 313,
	"illumise" => 314,
	"roselia" => 315,
	"gulpin" => 316,
	"swalot" => 317,
	"carvanha" => 318,
	"sharpedo" => 319,
	"wailmer" => 320,
	"wailord" => 321,
	"numel" => 322,
	"camerupt" => 323,
	"torkoal" => 324,
	"spoink" => 325,
	"grumpig" => 326,
	"spinda" => 327,
	"trapinch" => 328,
	"vibrava" => 329,
	"flygon" => 330,
	"cacnea" => 331,
	"cacturne" => 332,
	"swablu" => 333,
	"altaria" => 334,
	"zangoose" => 335,
	"seviper" => 336,
	"lunatone" => 337,
	"solrock" => 338,
	"barboach" => 339,
	"whiscash" => 340,
	"corphish" => 341,
	"crawdaunt" => 342,
	"baltoy" => 343,
	"claydol" => 344,
	"lileep" => 345,
	"cradily" => 346,
	"anorith" => 347,
	"armaldo" => 348,
	"feebas" => 349,
	"milotic" => 350,
	"castform" => 351,
	"kecleon" => 352,
	"shuppet" => 353,
	"banette" => 354,
	"duskull" => 355,
	"dusclops" => 356,
	"tropius" => 357,
	"chimecho" => 358,
	"absol" => 359,
	"wynaut" => 360,
	"snorunt" => 361,
	"glalie" => 362,
	"spheal" => 363,
	"sealeo" => 364,
	"walrein" => 365,
	"clamperl" => 366,
	"huntail" => 367,
	"gorebyss" => 368,
	"relicanth" => 369,
	"luvdisc" => 370,
	"bagon" => 371,
	"shelgon" => 372,
	"salamence" => 373,
	"beldum" => 374,
	"metang" => 375,
	"metagross" => 376,
	"regirock" => 377,
	"regice" => 378,
	"registeel" => 379,
	"latias" => 380,
	"latios" => 381,
	"kyogre" => 382,
	"groudon" => 383,
	"rayquaza" => 384,
	"jirachi" => 385,
	"deoxys" => 386,
	"turtwig" => 387,
	"grotle" => 388,
	"torterra" => 389,
	"chimchar" => 390,
	"monferno" => 391,
	"infernape" => 392,
	"piplup" => 393,
	"prinplup" => 394,
	"empoleon" => 395,
	"starly" => 396,
	"staravia" => 397,
	"staraptor" => 398,
	"bidoof" => 399,
	"bibarel" => 400,
	"kricketot" => 401,
	"kricketune" => 402,
	"shinx" => 403,
	"luxio" => 404,
	"luxray" => 405,
	"budew" => 406,
	"roserade" => 407,
	"cranidos" => 408,
	"rampardos" => 409,
	"shieldon" => 410,
	"bastiodon" => 411,
	"burmy" => 412,
	"wormadam" => 413,
	"mothim" => 414,
	"combee" => 415,
	"vespiquen" => 416,
	"pachirisu" => 417,
	"buizel" => 418,
	"floatzel" => 419,
	"cherubi" => 420,
	"cherrim" => 421,
	"shellos" => 422,
	"gastrodon" => 423,
	"ambipom" => 424,
	"drifloon" => 425,
	"drifblim" => 426,
	"buneary" => 427,
	"lopunny" => 428,
	"mismagius" => 429,
	"honchkrow" => 430,
	"glameow" => 431,
	"purugly" => 432,
	"chingling" => 433,
	"stunky" => 434,
	"skuntank" => 435,
	"bronzor" => 436,
	"bronzong" => 437,
	"bonsly" => 438,
	"mimejr." => 439,
	"happiny" => 440,
	"chatot" => 441,
	"spiritomb" => 442,
	"gible" => 443,
	"gabite" => 444,
	"garchomp" => 445,
	"munchlax" => 446,
	"riolu" => 447,
	"lucario" => 448,
	"hippopotas" => 449,
	"hippowdon" => 450,
	"skorupi" => 451,
	"drapion" => 452,
	"croagunk" => 453,
	"toxicroak" => 454,
	"carnivine" => 455,
	"finneon" => 456,
	"lumineon" => 457,
	"mantyke" => 458,
	"snover" => 459,
	"abomasnow" => 460,
	"weavile" => 461,
	"magnezone" => 462,
	"lickilicky" => 463,
	"rhyperior" => 464,
	"tangrowth" => 465,
	"electivire" => 466,
	"magmortar" => 467,
	"togekiss" => 468,
	"yanmega" => 469,
	"leafeon" => 470,
	"glaceon" => 471,
	"gliscor" => 472,
	"mamoswine" => 473,
	"porygonz" => 474,
	"gallade" => 475,
	"probopass" => 476,
	"dusknoir" => 477,
	"froslass" => 478,
	"rotom" => 479,
	"uxie" => 480,
	"mesprit" => 481,
	"azelf" => 482,
	"dialga" => 483,
	"palkia" => 484,
	"heatran" => 485,
	"regigigas" => 486,
	"giratina" => 487,
	"cresselia" => 488,
	"phione" => 489,
	"manaphy" => 490,
	"darkrai" => 491,
	"shaymin" => 492,
	"arceus" => 493,
	"victini" => 494,
	"snivy" => 495,
	"servine" => 496,
	"serperior" => 497,
	"tepig" => 498,
	"pignite" => 499,
	"emboar" => 500,
	"oshawott" => 501,
	"dewott" => 502,
	"samurott" => 503,
	"patrat" => 504,
	"watchog" => 505,
	"lillipup" => 506,
	"herdier" => 507,
	"stoutland" => 508,
	"purrloin" => 509,
	"liepard" => 510,
	"pansage" => 511,
	"simisage" => 512,
	"pansear" => 513,
	"simisear" => 514,
	"panpour" => 515,
	"simipour" => 516,
	"munna" => 517,
	"musharna" => 518,
	"pidove" => 519,
	"tranquill" => 520,
	"unfezant" => 521,
	"blitzle" => 522,
	"zebstrika" => 523,
	"roggenrola" => 524,
	"boldore" => 525,
	"gigalith" => 526,
	"woobat" => 527,
	"swoobat" => 528,
	"drilbur" => 529,
	"excadrill" => 530,
	"audino" => 531,
	"timburr" => 532,
	"gurdurr" => 533,
	"conkeldurr" => 534,
	"tympole" => 535,
	"palpitoad" => 536,
	"seismitoad" => 537,
	"throh" => 538,
	"sawk" => 539,
	"sewaddle" => 540,
	"swadloon" => 541,
	"leavanny" => 542,
	"venipede" => 543,
	"whirlipede" => 544,
	"scolipede" => 545,
	"cottonee" => 546,
	"whimsicott" => 547,
	"petilil" => 548,
	"lilligant" => 549,
	"basculin" => 550,
	"sandile" => 551,
	"krokorok" => 552,
	"krookodile" => 553,
	"darumaka" => 554,
	"darmanitan" => 555,
	"maractus" => 556,
	"dwebble" => 557,
	"crustle" => 558,
	"scraggy" => 559,
	"scrafty" => 560,
	"sigilyph" => 561,
	"yamask" => 562,
	"cofagrigus" => 563,
	"tirtouga" => 564,
	"carracosta" => 565,
	"archen" => 566,
	"archeops" => 567,
	"trubbish" => 568,
	"garbodor" => 569,
	"zorua" => 570,
	"zoroark" => 571,
	"minccino" => 572,
	"cinccino" => 573,
	"gothita" => 574,
	"gothorita" => 575,
	"gothitelle" => 576,
	"solosis" => 577,
	"duosion" => 578,
	"reuniclus" => 579,
	"ducklett" => 580,
	"swanna" => 581,
	"vanillite" => 582,
	"vanillish" => 583,
	"vanilluxe" => 584,
	"deerling" => 585,
	"sawsbuck" => 586,
	"emolga" => 587,
	"karrablast" => 588,
	"escavalier" => 589,
	"foongus" => 590,
	"amoonguss" => 591,
	"frillish" => 592,
	"jellicent" => 593,
	"alomomola" => 594,
	"joltik" => 595,
	"galvantula" => 596,
	"ferroseed" => 597,
	"ferrothorn" => 598,
	"klink" => 599,
	"klang" => 600,
	"klinklang" => 601,
	"tynamo" => 602,
	"eelektrik" => 603,
	"eelektross" => 604,
	"elgyem" => 605,
	"beheeyem" => 606,
	"litwick" => 607,
	"lampent" => 608,
	"chandelure" => 609,
	"axew" => 610,
	"fraxure" => 611,
	"haxorus" => 612,
	"cubchoo" => 613,
	"beartic" => 614,
	"cryogonal" => 615,
	"shelmet" => 616,
	"accelgor" => 617,
	"stunfisk" => 618,
	"mienfoo" => 619,
	"mienshao" => 620,
	"druddigon" => 621,
	"golett" => 622,
	"golurk" => 623,
	"pawniard" => 624,
	"bisharp" => 625,
	"bouffalant" => 626,
	"rufflet" => 627,
	"braviary" => 628,
	"vullaby" => 629,
	"mandibuzz" => 630,
	"heatmor" => 631,
	"durant" => 632,
	"deino" => 633,
	"zweilous" => 634,
	"hydreigon" => 635,
	"larvesta" => 636,
	"volcarona" => 637,
	"cobalion" => 638,
	"terrakion" => 639,
	"virizion" => 640,
	"tornadus" => 641,
	"thundurus" => 642,
	"reshiram" => 643,
	"zekrom" => 644,
	"landorus" => 645,
	"kyurem" => 646,
	"keldeo" => 647,
	"meloetta" => 648,
	"genesect" => 649,
	"chespin" => 650,
	"quilladin" => 651,
	"chesnaught" => 652,
	"fennekin" => 653,
	"braixen" => 654,
	"delphox" => 655,
	"froakie" => 656,
	"frogadier" => 657,
	"greninja" => 658,
	"bunnelby" => 659,
	"diggersby" => 660,
	"fletchling" => 661,
	"fletchinder" => 662,
	"talonflame" => 663,
	"scatterbug" => 664,
	"spewpa" => 665,
	"vivillon" => 666,
	"litleo" => 667,
	"pyroar" => 668,
	"flabébé" => 669,
	"floette" => 670,
	"florges" => 671,
	"skiddo" => 672,
	"gogoat" => 673,
	"pancham" => 674,
	"pangoro" => 675,
	"furfrou" => 676,
	"espurr" => 677,
	"meowstic" => 678,
	"honedge" => 679,
	"doublade" => 680,
	"aegislash" => 681,
	"spritzee" => 682,
	"aromatisse" => 683,
	"swirlix" => 684,
	"slurpuff" => 685,
	"inkay" => 686,
	"malamar" => 687,
	"binacle" => 688,
	"barbaracle" => 689,
	"skrelp" => 690,
	"dragalge" => 691,
	"clauncher" => 692,
	"clawitzer" => 693,
	"helioptile" => 694,
	"heliolisk" => 695,
	"tyrunt" => 696,
	"tyrantrum" => 697,
	"amaura" => 698,
	"aurorus" => 699,
	"sylveon" => 700,
	"hawlucha" => 701,
	"dedenne" => 702,
	"carbink" => 703,
	"goomy" => 704,
	"sliggoo" => 705,
	"goodra" => 706,
	"klefki" => 707,
	"phantump" => 708,
	"trevenant" => 709,
	"pumpkaboo" => 710,
	"gourgeist" => 711,
	"bergmite" => 712,
	"avalugg" => 713,
	"noibat" => 714,
	"noivern" => 715,
	"xerneas" => 716,
	"yveltal" => 717,
	"zygarde" => 718,
	"diancie" => 719,
	"hoopa" => 720,
	"volcanion" => 721,
	"rowlet" => 722,
	"dartrix" => 723,
	"decidueye" => 724,
	"litten" => 725,
	"torracat" => 726,
	"incineroar" => 727,
	"popplio" => 728,
	"brionne" => 729,
	"primarina" => 730,
	"pikipek" => 731,
	"trumbeak" => 732,
	"toucannon" => 733,
	"yungoos" => 734,
	"gumshoos" => 735,
	"grubbin" => 736,
	"charjabug" => 737,
	"vikavolt" => 738,
	"crabrawler" => 739,
	"crabominable" => 740,
	"oricorio" => 741,
	"cutiefly" => 742,
	"ribombee" => 743,
	"rockruff" => 744,
	"lycanroc" => 745,
	"wishiwashi" => 746,
	"mareanie" => 747,
	"toxapex" => 748,
	"mudbray" => 749,
	"mudsdale" => 750,
	"dewpider" => 751,
	"araquanid" => 752,
	"fomantis" => 753,
	"lurantis" => 754,
	"morelull" => 755,
	"shiinotic" => 756,
	"salandit" => 757,
	"salazzle" => 758,
	"stufful" => 759,
	"bewear" => 760,
	"bounsweet" => 761,
	"steenee" => 762,
	"tsareena" => 763,
	"comfey" => 764,
	"oranguru" => 765,
	"passimian" => 766,
	"wimpod" => 767,
	"golisopod" => 768,
	"sandygast" => 769,
	"palossand" => 770,
	"pyukumuku" => 771,
	"type:null" => 772,
	"silvally" => 773,
	"minior" => 774,
	"komala" => 775,
	"turtonator" => 776,
	"togedemaru" => 777,
	"mimikyu" => 778,
	"bruxish" => 779,
	"drampa" => 780,
	"dhelmise" => 781,
	"jangmoo" => 782,
	"hakamoo" => 783,
	"kommoo" => 784,
	"tapukoko" => 785,
	"tapulele" => 786,
	"tapubulu" => 787,
	"tapufini" => 788,
	"cosmog" => 789,
	"cosmoem" => 790,
	"solgaleo" => 791,
	"lunala" => 792,
	"nihilego" => 793,
	"buzzwole" => 794,
	"pheromosa" => 795,
	"xurkitree" => 796,
	"celesteela" => 797,
	"kartana" => 798,
	"guzzlord" => 799,
	"necrozma" => 800,
	"magearna" => 801,
	"marshadow" => 802,
	"poipole" => 803,
	"naganadel" => 804,
	"stakataka" => 805,
	"blacephalon" => 806,
	"zeraora" => 807,
	"meltan" => 808,
	"melmetal" => 809
);

function decodePokemonName($pokemon) {
	$data = preg_replace("/[^a-z0-9\%]/", "", strtolower($pokemon));
	$data = str_replace("mew-two", "mewtwo", $data);
	
	$pokemonName = null;
	$pokemonId = null;
	$matchLength = 0;
	
	if ( is_numeric($data) ) {
		$data = preg_replace("/[^a-z0-9\%]/", "", strtolower(POKEMON_ID_TO_NAME[$data]));
	}
	
	foreach(POKEMON_NAME_TO_ID as $pkmn => $pkmnId) {
		if ( strpos($data, $pkmn) !== false && $matchLength < strlen($pkmn) ) {
			$pokemonId = $pkmnId;
			$pokemonName = $pkmn;
			$matchLength = strlen($pkmn);
		}
	}
	
	if ( $pokemonId ) {
		$validName = POKEMON_ID_TO_NAME[$pokemonId];
		$forme = ucwords(str_replace($pokemonName, "", $data));
		
		if ( $forme == "Megax" ) {
			$forme = "Mega-X";
		} elseif ( $forme == "Megay" ) {
			$forme = "Mega-Y";
		}
	} else {
		$validName = $pokemon;
		$forme = "";
	}
	
	return array(
		"pokemon" => $validName,
		"forme" => $forme,
		"valid" => ($pokemonId != null)
	);
}

function decodePokemonLabel($pokemonData) {
	if ( $pokemonData["forme"] == "Mega-X" ) {
		$label = "Mega " . $pokemonData["pokemon"] . " X";

	} elseif ( $pokemonData["forme"] == "Mega-Y" ) {
		$label = "Mega " . $pokemonData["pokemon"] . " Y";

	} elseif ( $pokemonData["forme"] == "Mega" ) {
		$label = $pokemonData["forme"] . " " . $pokemonData["pokemon"];

	} elseif ( $pokemonData["forme"] == "Alola" ) {
		$label = $pokemonData["forme"] . " " . $pokemonData["pokemon"];

	} elseif ( $pokemonData["forme"] == "Primal" ) {
		$label = $pokemonData["forme"] . " " . $pokemonData["pokemon"];

	} elseif ( $pokemonData["forme"] == "Dawn" && $pokemonData["pokemon"] == "Necrozma" ) {
		$label = "Dawn Wings " . $pokemonData["pokemon"];
	} elseif ( $pokemonData["forme"] == "Dusk" && $pokemonData["pokemon"] == "Necrozma" ) {
		$label = "Dusk Mane " . $pokemonData["pokemon"];

	} elseif ( $pokemonData["forme"] == "Therian" || $pokemonData["forme"] == "Incarnate" ) {
		$label = $pokemonData["pokemon"] . "-" . $pokemonData["forme"];

	} elseif ( $pokemonData["forme"] != "" ) {
		$label = $pokemonData["pokemon"] . " " . $pokemonData["forme"];

	} else {
		$label = $pokemonData["pokemon"];
	}

	return $label;	
}

function getSpriteClass($pokemonData) {
	$class = "pkspr";
	
	if ( $pokemonData["valid"] ) {
		$class .= " pkmn-" . str_replace(" ", "-", strtolower($pokemonData["pokemon"]));
		
		if ( $pokemonData["forme"] != "" ) {
			$class .= " form-" . str_replace(" ", "-", strtolower($pokemonData["forme"]));
		}
		
		if ( $pokemonData["shiny"] ) {
			$class .= " color-shiny";
		}
	} else {
		$class .= " pkmn-unknown";
	}
	
	return $class;
}

function decodePokemonShowdown($showdown) {
	$lines = explode("\n", str_replace("\r", "", $showdown));
	
	$pokemonData = array(
		"pokemon"	=> "",
		"forme"		=> "",
		"nickname"	=> "",
		"heldItem"	=> "",
		"level"		=> 50,
		"ability"	=> "",
		"ivs"		=> array(),
		"evs"		=> array(),
		"nature"	=> "",
		"moves"		=> array("1" => "", "2" => "", "3" => "", "4" => ""),
		"gender"	=> "",
		"shiny"		=> false,
		"valid"		=> false
	);
	
	$moveCount = 1;
	
	foreach($lines as $line) {
		$data = trim($line);
		
		if ( $data == "" ) {
			// Empty line
		} elseif ( stripos($data, "Ability:") !== false ) {
			$pokemonData["ability"] = ucwords(strtolower(preg_replace("/Ability: */", "", $data)));
		} elseif ( stripos($data, "Level:") !== false ) {
			$pokemonData["level"] = preg_replace("/Level: */", "", $data);
		} elseif ( stripos($data, " Nature") !== false ) {
			$pokemonData["nature"] = ucwords(strtolower(preg_replace("/ *Nature.*/", "", $data)));
		} elseif ( stripos($data, "EVs:") !== false ) {
			$evData = preg_replace("/EVs: */", "", $data);
			$evSplit = explode("/", $evData);
			
			foreach($evSplit as $ev) {
				$evValue = (int)(preg_replace("/[^0-9]/", "", $ev));
				
				if ( stripos($ev, "HP") !== false )		$pokemonData["evs"]["hp"] = $evValue;
				if ( stripos($ev, "ATK") !== false )	$pokemonData["evs"]["atk"] = $evValue;
				if ( stripos($ev, "DEF") !== false )	$pokemonData["evs"]["def"] = $evValue;
				if ( stripos($ev, "SPA") !== false )	$pokemonData["evs"]["spa"] = $evValue;
				if ( stripos($ev, "SPD") !== false )	$pokemonData["evs"]["spd"] = $evValue;
				if ( stripos($ev, "SPE") !== false )	$pokemonData["evs"]["spe"] = $evValue;
			}
		} elseif ( stripos($data, "IVs:") !== false ) {
			$ivData = preg_replace("/IVs: */", "", $data);
			$ivSplit = explode("/", $ivData);
			
			foreach($ivSplit as $iv) {
				$ivValue = (int)(preg_replace("/[^0-9]/", "", $iv));
				
				if ( stripos($iv, "HP") !== false )		$pokemonData["ivs"]["hp"] = $ivValue;
				if ( stripos($iv, "ATK") !== false )	$pokemonData["ivs"]["atk"] = $ivValue;
				if ( stripos($iv, "DEF") !== false )	$pokemonData["ivs"]["def"] = $ivValue;
				if ( stripos($iv, "SPA") !== false )	$pokemonData["ivs"]["spa"] = $ivValue;
				if ( stripos($iv, "SPD") !== false )	$pokemonData["ivs"]["spd"] = $ivValue;
				if ( stripos($iv, "SPE") !== false )	$pokemonData["ivs"]["spe"] = $ivValue;
			}
		} elseif ( substr($data, 0, 1) == "-" ) {
			$move = preg_replace("/^\- */", "", $data);
			$pokemonData["moves"][$moveCount] = ucwords(strtolower($move));
			$moveCount++;
		} elseif ( stripos($data, "Shiny:") !== false ) {
			$pokemonData["shiny"] = (stripos($data, "Yes") !== false);
		} elseif ( stripos($data, "Happiness:") !== false ) {
			$pokemonData["happiness"] = (int)(preg_replace("/[^0-9]/", "", $iv));
		} else {
			if ( stripos($data, "(M)") !== false ) {
				$pokemonData["gender"] = "M";
			} elseif ( stripos($data, "(F)") !== false ) {
				$pokemonData["gender"] = "F";
			}
			
			if ( stripos($data, "@") !== false ) {
				$pokemonData["heldItem"] = ucwords(strtolower(preg_replace("/.*@ */", "", $data)));
			}
			
			$pokemon = preg_replace("/ *@.*/", "", $data);
			$nickname = "";
			
			if ( strpos($pokemon, "(") !== false ) {
				$nickname = preg_replace("/ *\(.*/", "", $pokemon);
				$pokemon = preg_replace("/.*\(/", "", preg_replace("/\).*/", "", $pokemon));
			}
			
			$pokemonData["nickname"] = $nickname;
			
			$nameData = decodePokemonName($pokemon);
			$pokemonData["pokemon"] = $nameData["pokemon"];
			$pokemonData["forme"] = $nameData["forme"];
			$pokemonData["valid"] = $nameData["valid"];
		}
	}
	
	return $pokemonData;
}

function encodePokemonShowdown($pokemonData) {
	$output = "";
	
	if ( $pokemonData["pokemon"] != $pokemonData["nickname"] && $pokemonData["nickname"] != "" ) {
		$output .= $pokemonData["nickname"] . " (" . $pokemonData["pokemon"] . "-" . $pokemonData["forme"] . ")";
	} else {
		$output .= $pokemonData["pokemon"];
		if ( $pokemonData["forme"] != "" ) $output .= "-" . $pokemonData["forme"];
	}
	
	if ( isset($pokemonData["gender"]) ) {
		if ( $pokemonData["gender"] != "" ) $output .= " (" . $pokemonData["gender"] . ")";
	}
	
	if ( isset($pokemonData["heldItem"]) ) {
		if ( $pokemonData["heldItem"] != "" ) {
			$output .= " @ " . $pokemonData["heldItem"];
		}
	}
	
	$output .= "\r\n";
	
	if ( $pokemonData["shiny"] ) $output .= "Shiny: Yes\r\n";
	$output .= "Ability: " . $pokemonData["ability"] . "\r\n";
	
	if ( isset($pokemonData["level"]) ) {
		$output .= "Level: " . $pokemonData["level"] . "\r\n";
	}
	
	if ( isset($pokemonData["happiness"]) ) {
		$output .= "Happiness: " . $pokemonData["happiness"] . "\r\n";
	}
	
	if ( isset($pokemonData["evs"]) ) {
		$evData = "";
	
		foreach($pokemonData["evs"] as $evType => $evValue) {
			$evText = "";
			if ( $evType == "hp"  ) $evText = "HP";
			if ( $evType == "atk" ) $evText = "Atk";
			if ( $evType == "def" ) $evText = "Def";
			if ( $evType == "spa" ) $evText = "SpA";
			if ( $evType == "spd" ) $evText = "SpD";
			if ( $evType == "spe" ) $evText = "Spe";
			
			$evData .= $evValue . " " . $evText . " / ";
		}
		
		if ( $evData != "" ) {
			$output .= "EVs: " . substr($evData, 0, -3) . "\r\n";
		}
	}
	
	if ( isset($pokemonData["nature"]) ) {
		if ( $pokemonData["nature"] != "" ) $output .= $pokemonData["nature"] . " Nature\r\n";
	}
	
	if ( isset($pokemonData["ivs"]) ) {
		$ivData = "";
		
		foreach($pokemonData["ivs"] as $ivType => $ivValue) {
			$ivText = "";
			if ( $ivType == "hp"  ) $ivText = "HP";
			if ( $ivType == "atk" ) $ivText = "Atk";
			if ( $ivType == "def" ) $ivText = "Def";
			if ( $ivType == "spa" ) $ivText = "SpA";
			if ( $ivType == "spd" ) $ivText = "SpD";
			if ( $ivType == "spe" ) $ivText = "Spe";
	
			$ivData .= $ivValue . " " . $ivText . " / ";
		}
		
		if ( $ivData != "" ) {
			$output .= "IVs: " . substr($ivData, 0, -3) . "\r\n";
		}
	}
	
	if ( isset($pokemonData["moves"]) ) {
		if ( $pokemonData["moves"]["1"] != "" ) $output .= "- " . $pokemonData["moves"]["1"] . "\r\n";
		if ( $pokemonData["moves"]["2"] != "" ) $output .= "- " . $pokemonData["moves"]["2"] . "\r\n";
		if ( $pokemonData["moves"]["3"] != "" ) $output .= "- " . $pokemonData["moves"]["3"] . "\r\n";
		if ( $pokemonData["moves"]["4"] != "" ) $output .= "- " . $pokemonData["moves"]["4"] . "\r\n";
	}
	
	return $output;
}

function getFlagEmoji($countryCode) {
	$flagOffset = 0x1F1E6;
	$asciiOffset = 0x41;

	$country = ISO_3_TO_2[$countryCode];
	$firstChar = ord(substr($country, 0, 1)) - $asciiOffset + $flagOffset;
	$secondChar = ord(substr($country, 1, 1)) - $asciiOffset + $flagOffset;

	$flag = mb_chr($firstChar) . mb_chr($secondChar);
	return $flag;
}

function getSeasonDropdownData() {
	$seasonPeriods = json_decode(file_get_contents("https://pokecal-dev.codearoundcorners.com/api.php?command=listPeriods&product=Video%20Game&onlyFormat"), true);
	
	$maximumSeason = 0;	
	$maximumHistory = 3;

	$searchData = array();
	
	foreach( $seasonPeriods["data"] as $season => $seasonPeriod ) {
		if ( $season > $maximumSeason ) $maximumSeason = $season;
	}
	
	for( $currentSeason = $maximumSeason; $currentSeason > ($maximumSeason - $maximumHistory); $currentSeason-- ) {
		$searchData[$currentSeason] = array();
		
		foreach( $seasonPeriods["data"] as $season => $seasonPeriod ) {
			if ( $season == $currentSeason ) {
				foreach ( $seasonPeriod["periods"] as $periodId => $period ) {
					$arrayId = $period["startDate"] . "-" . $periodId;
					$searchData[$currentSeason][$arrayId] = $period;
				}
			}
		}
	}
	
	return array(
		"maximumSeason"		=> $maximumSeason,
		"maximumHistory"	=> $maximumHistory,
		"periods"			=> $seasonPeriods,
		"data"				=> $searchData
	);
}

function makeSearchBarHtml($periodData) {
?>
	<hr />
    <div class="container">
		<div class="input-group input-group-sm">
<?
	if ( $periodData !== null ) {
?>
			<div class="input-group-prepend">
				<button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" id="current-season">All Seasons</button>
				<? echo makeSeasonDropdownHtml($periodData); ?>
			</div>
<?
	}
?>
			<input type="text" class="form-control" aria-label="Search" placeholder="Search..." id="searchFilter" />
			<div class="input-group-append">
				<button class="btn btn-primary" type="button"><i class="fas fa-search"></i></button>
			</div>
  		</div>
    </div>
    <hr />
<?
}

function makeSeasonDropdownHtml($periodData) {
	$dropdownHtml = "<a href='#!' class='dropdown-item season-selection' data-season='-1'>All Seasons</a>\n";
	
	for( $currentSeason = $periodData["maximumSeason"]; $currentSeason > ($periodData["maximumSeason"] - $periodData["maximumHistory"]); $currentSeason-- ) {
		$dropdownHtml .= "<h6 class='dropdown-header'>" . $currentSeason . " Season</h6>\n";
		
		if ( isset($periodData["periods"]["data"][$currentSeason]) ) {
			$startDate = $periodData["periods"]["data"][$currentSeason]["startDate"];
			$endDate = $periodData["periods"]["data"][$currentSeason]["endDate"];
		} else {
			$startDate = "";
			$endDate = "";
		}
		
		$dropdownHtml .= "<a href='#!' class='dropdown-item season-selection' data-season='" . $currentSeason . "' data-start='";
		$dropdownHtml .= $startDate . "' data-end='" . $endDate . "'>All Events</a>\n";
		
		foreach($periodData["data"][$currentSeason] as $arrayId => $period) {
			$startDate = $period["startDate"];
			$endDate = $period["endDate"];
			$periodName = $period["name"];
		
			$dropdownHtml .= "<a href='#!' class='dropdown-item season-selection' data-season='" . $currentSeason . "' data-start='";
			$dropdownHtml .= $startDate . "' data-end='" . $endDate . "'>" . $periodName . "</a>\n";
		}
	}
	
	$dropdownHtml .= "<h6 class='dropdown-header'>Older Events</h6>";
	$dropdownHtml .= "<a href='#!' class='dropdown-item season-selection' data-season='-2'>Older Seasons</a>\n";
	
	return "<div class='dropdown-menu' style='font-size: 12px;'>" . $dropdownHtml . "</div>";
}

function makeSeasonDropdownJs($periodData) {
?>
	<script type="text/javascript">
<?
	if ( $periodData !== null ) {
		$firstSeason = 2000;
		$olderSeasonFilter = $firstSeason;
		
		for( $season = ($firstSeason + 1); $season <= ($periodData["maximumSeason"] - $periodData["maximumHistory"]); $season++ ) {
			$olderSeasonFilter .= " OR " . $season;
		}
?>
		$(".season-selection").click(function() {
			var season = $(this).attr("data-season");
			var dateStart = $(this).attr("data-start");
			var dateEnd = $(this).attr("data-end");
			var dataLabel = $(this).text();
			
			if ( season > 0 ) {
				$("#current-season").text("(" + season + ") " + $(this).text());
			} else {
				$("#current-season").text($(this).text());
			}
			
			filter = FooTable.get(".period-search").use(FooTable.Filtering);
			filter.removeFilter("season");
			
			if ( season == -1 ) {
				
			} else if ( season == -2 ) {
				filter.addFilter("season", "<? echo $olderSeasonFilter; ?>", ["season"]);
			} else if ( dateStart == "" && dateEnd == "" ) {
				filter.addFilter("season", season, ["season"]);
			} else {
				var filterText = dateStart.replace(/\-/g, "");
				var checkDate = new Date(dateStart);
				var lastDate = new Date(dateEnd);
				
				while ( checkDate <= lastDate ) {
					checkDate.setDate(checkDate.getDate() + 1);
					filterText += " OR " + checkDate.toISOString().substr(0, 10).replace(/\-/g, "");
				}
				
				filter.addFilter("season", filterText, ["eventDate"]);
			}
			
			filter.filter();
		});
<?
	}
?>
		$("#searchFilter").on("change", function() {
			filterText = $(this).val();
			filter = FooTable.get("#events").use(FooTable.Filtering);
			
			if ( filterText == "" || filterText.length < 3 ) {
				filter.removeFilter("generic");
			} else {
				filter.addFilter("generic", filterText);
			}			

			filter.filter();
		});
	</script>
<?php
}
?>