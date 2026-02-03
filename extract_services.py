import openpyxl
import json
import re

def clean_price(price_str):
    if price_str is None:
        return 0
    if isinstance(price_str, (int, float)):
        return int(price_str)
    # Remove $, commas, dots, and spaces
    cleaned = re.sub(r'[^\d]', '', str(price_str))
    return int(cleaned) if cleaned else 0

def extract_services(file_path):
    wb = openpyxl.load_workbook(file_path, data_only=True)
    sheet = wb.active
    
    services = []
    headers = None
    for row in sheet.iter_rows(values_only=True):
        if not headers:
            headers = [str(h).lower() if h is not None else f"col_{i}" for i, h in enumerate(row)]
            continue
        
        # 'Categoria': col 0, 'SKU': col 1, 'Nombre del Servicio': col 2, 'Precio': col 3
        cat = row[0]
        sku = row[1]
        name = row[2]
        price = clean_price(row[3])
        
        if name and str(name).strip():
            services.append({
                "category": str(cat).strip() if cat else "General",
                "sku": str(sku).strip() if sku else "",
                "name": str(name).strip(),
                "price": price,
                "duration": 60 # Default duration
            })
    
    with open("services_to_import.json", "w", encoding="utf-8") as f:
        json.dump(services, f, indent=4, ensure_ascii=False)
    
    print(f"Extracted {len(services)} services to services_to_import.json")

if __name__ == "__main__":
    extract_services("Listado de Precios de Servicios.xlsx")
