import os

games_dir = 'images/games'
files = sorted(os.listdir(games_dir))

win568_files = [f for f in files if f.startswith('568win-')]
print(f"Found {len(win568_files)} 568win game image files.")

sql_content = "INSERT INTO `demo_games` (`demo_name`, `demo_gamelink`, `demo_provider`) VALUES\n"
values = [f"('{f}', '#', '568win')" for f in win568_files]
sql_content += ",\n".join(values) + ";\n"

with open('seed_568win_games.sql', 'w', encoding='utf-8') as f:
    f.write(sql_content)

print("Generated seed_568win_games.sql successfully.")
