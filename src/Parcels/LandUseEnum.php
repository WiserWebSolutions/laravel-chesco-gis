<?php

namespace WiserWebSolutions\ChesCoGis\Parcels;

/**
 * Chester County assessment office land use codes (the `LUC` parcel field).
 *
 * @see https://www.chesco.org/DocumentCenter/View/8673/Land_Use_Codes
 */
enum LandUseEnum: string
{
    case R_40 = 'R-40';
    case R_90 = 'R-90';
    case C_10 = 'C-10';
    case C_20 = 'C-20';
    case C_30 = 'C-30';
    case C_35 = 'C-35';
    case C_40 = 'C-40';
    case C_41 = 'C-41';
    case C_50 = 'C-50';
    case C_60 = 'C-60';
    case C_65 = 'C-65';
    case C_67 = 'C-67';
    case C_70 = 'C-70';
    case C_80 = 'C-80';
    case C_81 = 'C-81';
    case C_90 = 'C-90';
    case C_91 = 'C-91';
    case C_92 = 'C-92';
    case C_93 = 'C-93';
    case C_94 = 'C-94';
    case C_95 = 'C-95';
    case C_96 = 'C-96';
    case F_40 = 'F-40';
    case R_61 = 'R-61';
    case V_11 = 'V-11';
    case V_35 = 'V-35';
    case V_65 = 'V-65';
    case V_67 = 'V-67';
    case E_10 = 'E-10';
    case E_11 = 'E-11';
    case E_12 = 'E-12';
    case E_13 = 'E-13';
    case E_20 = 'E-20';
    case E_30 = 'E-30';
    case E_40 = 'E-40';
    case E_50 = 'E-50';
    case E_60 = 'E-60';
    case E_61 = 'E-61';
    case E_62 = 'E-62';
    case E_63 = 'E-63';
    case E_70 = 'E-70';
    case E_71 = 'E-71';
    case E_80 = 'E-80';
    case E_90 = 'E-90';
    case F_10 = 'F-10';
    case F_20 = 'F-20';
    case F_80 = 'F-80';
    case M_10 = 'M-10';
    case M_20 = 'M-20';
    case M_25 = 'M-25';
    case M_30 = 'M-30';
    case N_01 = 'N-01';
    case R_10 = 'R-10';
    case R_20 = 'R-20';
    case R_30 = 'R-30';
    case R_50 = 'R-50';
    case R_55 = 'R-55';
    case R_60 = 'R-60';
    case R_70 = 'R-70';
    case R_80 = 'R-80';
    case R_95 = 'R-95';
    case T_10 = 'T-10';
    case V_10 = 'V-10';
    case V_12 = 'V-12';
    case V_13 = 'V-13';
    case V_14 = 'V-14';
    case V_50 = 'V-50';
    case V_55 = 'V-55';
    case U_03 = 'U-03';
    case U_04 = 'U-04';

    public function description(): string
    {
        return match ($this) {
            self::R_40 => 'Apartments (4-19 Units)',
            self::R_90 => 'Apt Complex (20 or more units)',
            self::C_10 => 'Banks, Savings & Loans',
            self::C_20 => 'Gas Station',
            self::C_30 => 'Restaurants, Stores (Retail)',
            self::C_35 => 'Condominium Stores',
            self::C_40 => 'Motels, Hotels',
            self::C_41 => 'Nursing Homes',
            self::C_50 => 'Shopping Centers',
            self::C_60 => 'Office Bldgs/Laboratory/Library',
            self::C_65 => 'Office Condo',
            self::C_67 => 'Office Condo Common Law',
            self::C_70 => 'Commercial Garage/Shop/Car Dealers',
            self::C_80 => 'Warehouse',
            self::C_81 => 'Storage Tanks',
            self::C_90 => 'Entertainment, Recreation',
            self::C_91 => 'Recreation (Private)',
            self::C_92 => 'Mobile Home Parks (4+)',
            self::C_93 => 'Burial Grounds/Mausoleum',
            self::C_94 => 'Airports',
            self::C_95 => 'Private Schools',
            self::C_96 => 'Commercial OBY only',
            self::F_40 => 'Mushroom, Horticultural, etc',
            self::R_61 => 'Dwelling W/Comm Use Primary Comm',
            self::V_11 => 'Vacant Land Commercial',
            self::V_35 => 'Condo Store/Vac Common Element',
            self::V_65 => 'Condo Office/Vac Common Element',
            self::V_67 => 'Condo Common Law Office/Common Area',
            self::E_10 => 'Churches',
            self::E_11 => 'Cemeteries',
            self::E_12 => 'Service Connected',
            self::E_13 => 'Chester County Property',
            self::E_20 => 'Schools',
            self::E_30 => 'Public Utilities',
            self::E_40 => 'Railroads',
            self::E_50 => 'Hospitals, etc.',
            self::E_60 => 'State',
            self::E_61 => 'State Parks',
            self::E_62 => 'Federal',
            self::E_63 => 'Federal Government Parks',
            self::E_70 => "Local Gov't (Townships & Boroughs)",
            self::E_71 => "Local Gov't Parks",
            self::E_80 => 'Non Profit Organizations',
            self::E_90 => 'Fire Companies',
            self::F_10 => 'Farm 10-19.99 Acres',
            self::F_20 => 'Farm 20 to 79.99 Acres',
            self::F_80 => 'Farm 80 acres and over',
            self::M_10 => 'Heavy Industrial',
            self::M_20 => 'Light Industrial',
            self::M_25 => 'Light Industrial Condominium',
            self::M_30 => 'Quarry/Landfill',
            self::N_01 => 'Not Assessed in Chester County',
            self::R_10 => 'Single Family/Cabin',
            self::R_20 => 'Two Family',
            self::R_30 => 'Multi Family/Dorms/Single',
            self::R_50 => 'Condominium',
            self::R_55 => 'Town House (Common Law Condo)',
            self::R_60 => 'Dwelling W/Comm Use Primary Res',
            self::R_70 => 'Mobile Home',
            self::R_80 => 'Barns, Stables, Pools, Misc Bldgs',
            self::R_95 => 'Common Elements - Not Open Space',
            self::T_10 => 'Trailers and Mobile Homes',
            self::V_10 => 'Vacant Land Residential',
            self::V_12 => 'Open Space',
            self::V_13 => 'Road Beds, R/W, Access Way',
            self::V_14 => 'Basins, Drainage Controls',
            self::V_50 => 'Condo/Vacant Common Element',
            self::V_55 => 'Condo Common Law/Common Area',
            self::U_03 => 'Public Utilities',
            self::U_04 => 'Railroads',
        };
    }

    /**
     * The {@see PropertyClassEnum} this land use code rolls up to on the parcel's `CLASS` field.
     */
    public function propertyClass(): PropertyClassEnum
    {
        return match ($this) {
            self::R_40, self::R_90 => PropertyClassEnum::Apartment,
            self::C_10, self::C_20, self::C_30, self::C_35, self::C_40, self::C_41,
            self::C_50, self::C_60, self::C_65, self::C_67, self::C_70, self::C_80,
            self::C_81, self::C_90, self::C_91, self::C_92, self::C_93, self::C_94,
            self::C_95, self::C_96, self::F_40, self::R_61, self::V_11, self::V_35,
            self::V_65, self::V_67 => PropertyClassEnum::Commercial,
            self::E_10, self::E_11, self::E_12, self::E_13, self::E_20, self::E_30,
            self::E_40, self::E_50, self::E_60, self::E_61, self::E_62, self::E_63,
            self::E_70, self::E_71, self::E_80, self::E_90 => PropertyClassEnum::Exempt,
            self::F_10, self::F_20, self::F_80 => PropertyClassEnum::Farm,
            self::M_10, self::M_20, self::M_25, self::M_30 => PropertyClassEnum::Industrial,
            self::N_01 => PropertyClassEnum::NotAssessed,
            self::R_10, self::R_20, self::R_30, self::R_50, self::R_55, self::R_60,
            self::R_70, self::R_80, self::R_95, self::T_10, self::V_10, self::V_12,
            self::V_13, self::V_14, self::V_50, self::V_55 => PropertyClassEnum::Residential,
            self::U_03, self::U_04 => PropertyClassEnum::Utility,
        };
    }
}
