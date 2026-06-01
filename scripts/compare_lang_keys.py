import re
from pathlib import Path


def parse_php_keys(path):
    text = Path(path).read_text(encoding='utf-8')
    keys = set(re.findall(r"['\"]([A-Za-z0-9_]+(?:\.[A-Za-z0-9_]+)*)['\"]\s*=>", text))
    nested = set()
    for m in re.finditer(r"['\"]([A-Za-z0-9_]+)['\"]\s*=>\s*\[", text):
        prefix = m.group(1)
        start = m.end()
        depth = 1
        i = start
        while i < len(text) and depth:
            if text[i] == '[':
                depth += 1
            elif text[i] == ']':
                depth -= 1
            i += 1
        block = text[start:i-1]
        for sub in re.findall(r"['\"]([A-Za-z0-9_]+)['\"]\s*=>", block):
            nested.add(f"{prefix}.{sub}")
    return keys | nested


en = parse_php_keys(Path('resources/lang/en/messages.php'))
my = parse_php_keys(Path('resources/lang/my/messages.php'))
only_en = sorted(en - my)
only_my = sorted(my - en)
print('only_en_count', len(only_en))
print('only_my_count', len(only_my))
if only_en:
    print('\n--- keys only in en/messages.php ---')
    print('\n'.join(only_en))
if only_my:
    print('\n--- keys only in my/messages.php ---')
    print('\n'.join(only_my))
