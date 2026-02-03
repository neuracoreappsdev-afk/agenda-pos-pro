import openpyxl

def inspect_import_file(file_path):
    wb = openpyxl.load_workbook(file_path, data_only=True)
    sheet = wb.active
    print(f"File: {file_path}")
    print(f"Sheet: {sheet.title}")
    for row in list(sheet.iter_rows(values_only=True))[:10]:
        print(row)

if __name__ == "__main__":
    inspect_import_file("Importacion de Servicios 2025.xlsx")
