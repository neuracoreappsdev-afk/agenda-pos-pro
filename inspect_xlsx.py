import openpyxl

def check_col4(file_path):
    wb = openpyxl.load_workbook(file_path, data_only=True)
    sheet = wb.active
    for row in list(sheet.iter_rows(values_only=True))[1:15]:
        print(f"Service: {row[2]}, Price: {row[3]}, Col4: {row[4]}")

if __name__ == "__main__":
    check_col4("Listado de Precios de Servicios.xlsx")
