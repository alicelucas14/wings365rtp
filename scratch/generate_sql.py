import os

games_dir = 'images/games'
files = sorted(os.listdir(games_dir))

win568_files = [f for f in files if f.startswith('568win-')]
print(f"Found {len(win568_files)} 568win game image files.")

sql_content = "DELETE FROM `demo_games` WHERE LOWER(demo_provider) = '568win';\n\n"
sql_content += "INSERT INTO `demo_games` (`demo_provider`, `game_title`, `demo_name`, `demo_gamelink`) VALUES\n"
values = []
for f in win568_files:
    base_name = os.path.splitext(f)[0]
    parts = base_name.split('-')
    if len(parts) >= 2:
        title = f"{parts[0].upper()} Game {parts[1]}"
    else:
        title = base_name.replace('-', ' ').title()
    values.append(f"('568win', '{title}', '{f}', '#')")

sql_content += ",\n".join(values) + ";\n"

with open('seed_568win_games.sql', 'w', encoding='utf-8') as outfile:
    outfile.write(sql_content)

print("Generated seed_568win_games.sql with DELETE + INSERT successfully.")
